<?php

declare(strict_types=1);

namespace Tests\Application\Actions\FileFinder\DocumentType;

use DPR\API\Application\Actions\ActionPayload;
use DPR\API\Domain\Models\DocumentType;
use DI\Container;
use DPR\API\Infrastructure\Persistence\DAO\DAOFactory;
use DPR\API\Infrastructure\Persistence\DAO\FileFinder\DocumentTypeDAO;
use PDOException;
use Prophecy\Argument;
use Tests\TestCase;

class GetDocumentTypesActionTest extends TestCase
{

    public function getAllDocumentTypesCases()
    {
        return array(
            # 1 active Document Types
            array(
                array("dot_id" => 1, "dot_title" => "General", "dot_active" => 1),
            ),
            # 1 inactive Document Types
            array(
                array("dot_id" => 3, "dot_title" => "Instruction Manuals", "dot_active" => 0),
            ),
            # 5 Document Types active + nonactive
            array(
                array("dot_id" => 1, "dot_title" => "General", "dot_active" => 1),
                array("dot_id" => 3, "dot_title" => "Instruction Manuals", "dot_active" => 0),
                array("dot_id" => 7, "dot_title" => "Forms", "dot_active" => 1),
                array("dot_id" => 9, "dot_title" => "Staff Directives/Guidelines", "dot_active" => 1),
                array("dot_id" => 11, "dot_title" => "Corporate Policies", "dot_active" => 0)
            ),
            # No Document Types
            array(array())
        );
    }

    /** 
     * @test 
     * @dataProvider getAllDocumentTypesCases
     */
    public function getsAllDocumentTypes($documentTypeData)
    {
        $app = $this->getAppInstance();
        /** @var Container $container */
        $container = $app->getContainer();

        $documentTypes = [];
        foreach ($documentTypeData as $data) {
            $documentTypes[] = new DocumentType($data);
        }

        # Given a list of Document Types in the DB
        $documentTypeDAOProphecy = $this->prophesize(DocumentTypeDAO::class);
        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);
        $daoFactoryProphecy
            ->createDocumentTypeDAO()
            ->willReturn($documentTypeDAOProphecy->reveal())
            ->shouldBeCalledOnce();

        $documentTypeDAOProphecy
            ->getDocumentTypes(Argument::any())
            ->willReturn($documentTypes)
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(DocumentTypeDAO::class, $documentTypeDAOProphecy->reveal());

        # When the GET api/filefinder/documenttypes endpoint is reached
        $request = $this->createRequest('GET', '/api/filefinder/documenttypes');
        $response = $app->handle($request);

        # All the Document Types from the DB are retrieved
        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(200, ['documenttypes' => $documentTypes]);
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

        # Given that the DocumentTypeDAO throws a PDOException
        $documentTypeDAOProphecy
            ->getDocumentTypes(Argument::any())
            ->willThrow(new PDOException("Connection failed: SQLSTATE[HY000] [2002] Connection refused"))
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(DocumentTypeDAO::class, $documentTypeDAOProphecy->reveal());

        # When the GET api/filefinder/documenttypes endpoint is reached
        $request = $this->createRequest('GET', '/api/filefinder/documenttypes');
        $response = $app->handle($request);

        # The server responds with status code 500, and an error message
        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(500, ['message' => "Could not retrieve list of Document Types: Connection failed: SQLSTATE[HY000] [2002] Connection refused"]);
        $serializedExpectedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedExpectedPayload, $payload);
    }
}
