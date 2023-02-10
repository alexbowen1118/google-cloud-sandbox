<?php

declare(strict_types=1);

namespace Tests\Application\Actions\FileFinder\DocumentType;

use DPR\API\Application\Actions\ActionPayload;
use DPR\API\Domain\Models\DocumentType;
use DI\Container;
use DPR\API\Infrastructure\Persistence\DAO\DAOFactory;
use DPR\API\Infrastructure\Persistence\DAO\FileFinder\DocumentTypeDAO;
use PDOException;
use Tests\TestCase;

class DocumentTypeUsageChangeActionTest extends TestCase
{

    public function changeUsageStatusCases()
    {
        $randomIds = range(1, 50);
        shuffle($randomIds);
        $randomIds = array_chunk($randomIds, 5)[rand(0, 4)];
        $titles = ["General", "Staff Directives/Guidelines", "Forms"];
        for ($i = 0; $i < 3; $i++) {
            $documentType = new DocumentType(["dot_id" => $randomIds[$i], "dot_title" => $titles[$i], "dot_active" => rand(0, 1)]);
            $data[] = array(
                array("documentTypeObject" => $documentType)
            );
        }
        return $data;
    }

    /** 
     * @test 
     * @dataProvider changeUsageStatusCases
     */
    public function changeDocumentTypeActiveStatus($testData)
    {
        $app = $this->getAppInstance();
        /** @var Container $container */
        $container = $app->getContainer();

        $documentTypeObject = $testData["documentTypeObject"];
        $documentTypeId = (int) $documentTypeObject->id;

        # Given a DB table for Document Type and an id for a DocumentType
        $documentTypeDAOProphecy = $this->prophesize(DocumentTypeDAO::class);
        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);
        $daoFactoryProphecy
            ->createDocumentTypeDAO()
            ->willReturn($documentTypeDAOProphecy->reveal())
            ->shouldBeCalledOnce();

        $documentTypeDAOProphecy
            ->switchActiveStatusForId($documentTypeId)
            ->willReturn($documentTypeObject)
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(DocumentTypeDAO::class, $documentTypeDAOProphecy->reveal());

        # When the PATCH api/filefinder/documenttypes/{id}/active endpoint is reached with an id
        $request = $this->createRequest('PATCH', "/api/filefinder/documenttypes/${documentTypeId}/active");
        $response = $app->handle($request);

        # A message with the updated usage status of the Document Type is returned
        $expectedMessage = "Switched Document Type '$documentTypeObject->title' usage status to " . ($documentTypeObject->active ? "Active" : "Inactive");
        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(200, [
            "message" => $expectedMessage
        ]);
        $serializedExpectedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedExpectedPayload, $payload);
    }

    /** @test */
    public function willReturnServerError()
    {
        $app = $this->getAppInstance();
        /** @var Container $container */
        $container = $app->getContainer();

        $randId = rand(0, 50);

        $documentTypeDAOProphecy = $this->prophesize(DocumentTypeDAO::class);
        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);
        $daoFactoryProphecy
            ->createDocumentTypeDAO()
            ->willReturn($documentTypeDAOProphecy->reveal())
            ->shouldBeCalledOnce();

        # Given that the Document Type DAO throws a PDOException
        $documentTypeDAOProphecy
            ->switchActiveStatusForId($randId)
            ->willThrow(new PDOException("Error 1836 SQLSTATE[HY000] Running in read-only mode"))
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(DocumentTypeDAO::class, $documentTypeDAOProphecy->reveal());

        # When the PATCH api/filefinder/documenttypes/{id}/active endpoint is reached
        $request = $this->createRequest('PATCH', "/api/filefinder/documenttypes/$randId/active");
        $response = $app->handle($request);

        # The server responds with status code 500, and an error message
        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(
            500,
            ['message' => "Failed to switch active status: Error 1836 SQLSTATE[HY000] Running in read-only mode"]
        );
        $serializedExpectedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);
        $this->assertEquals($serializedExpectedPayload, $payload);
    }
}
