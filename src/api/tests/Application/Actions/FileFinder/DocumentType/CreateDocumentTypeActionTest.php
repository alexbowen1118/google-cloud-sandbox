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

class CreateDocumentTypeActionTest extends TestCase
{

    public function createDocumentTypeCases()
    {
        $randomIds = range(1, 50);
        shuffle($randomIds);
        $randomIds = array_chunk($randomIds, 5)[rand(0, 4)];
        $titles = ["General", "Staff Directives/Guidelines", "Forms"];

        $data = array();
        for ($i = 0; $i < 3; $i++) {
            $documentType = new DocumentType(["dot_id" => $randomIds[$i], "dot_title" => $titles[$i], "dot_active" => 1]);
            $data[] = array(
                array(
                    "newTitle" => $titles[$i], "returnId" => $randomIds[$i],
                    "expectedObject" => $documentType
                )
            );
        }
        return $data;
    }

    /** 
     * @test 
     * @dataProvider createDocumentTypeCases
     */
    public function createDocumentType($testData)
    {
        $app = $this->getAppInstance();
        /** @var Container $container */
        $container = $app->getContainer();

        # Given a DB table for Document Type and a new Document Type name
        $documentTypeDAOProphecy = $this->prophesize(DocumentTypeDAO::class);
        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);
        $daoFactoryProphecy
            ->createDocumentTypeDAO()
            ->willReturn($documentTypeDAOProphecy->reveal())
            ->shouldBeCalledOnce();

        $documentTypeDAOProphecy
            ->addNewDocumentType($testData["newTitle"])
            ->willReturn($testData["returnId"])
            ->shouldBeCalledOnce();

        $documentTypeDAOProphecy
            ->getById($testData["returnId"])
            ->willReturn($testData["expectedObject"])
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(DocumentTypeDAO::class, $documentTypeDAOProphecy->reveal());

        # When the POST api/filefinder/documenttypes endpoint is reached with the new name
        $requestHeaders = [
            'HTTP_ACCEPT' => 'application/json',
        ];
        $request = $this->createRequest('POST', '/api/filefinder/documenttypes', $requestHeaders);
        $request = $request->withParsedBody(array("title" => $testData["newTitle"]));
        $response = $app->handle($request);

        # The newly created Document Type is returned
        $newTitle = $testData["newTitle"];
        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(200, [
            "message" => "Created new Document Type '$newTitle'",
            "data" => $testData["expectedObject"]
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

        $documentTypeDAOProphecy = $this->prophesize(DocumentTypeDAO::class);
        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);
        $daoFactoryProphecy
            ->createDocumentTypeDAO()
            ->willReturn($documentTypeDAOProphecy->reveal())
            ->shouldBeCalledOnce();

        # Given that the Document Type DAO throws a PDOException
        $documentTypeDAOProphecy
            ->addNewDocumentType("New Document Type")
            ->willThrow(new PDOException("Error 1169 SQLSTATE[23000]: Can't write, because of unique constraint, to table 'document_type'"))
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(DocumentTypeDAO::class, $documentTypeDAOProphecy->reveal());

        # When the POST api/filefinder/documenttypes endpoint is reached
        $request = $this->createRequest('POST', '/api/filefinder/documenttypes');
        $request = $request->withParsedBody(["title" => "New Document Type"]);
        $response = $app->handle($request);

        # The server responds with status code 500, and an error message
        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(
            500,
            ['message' => "Failed to create new Document Type: Error 1169 SQLSTATE[23000]: Can't write, because of unique constraint, to table 'document_type'"]
        );
        $serializedExpectedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);
        $this->assertEquals($serializedExpectedPayload, $payload);
    }
}
