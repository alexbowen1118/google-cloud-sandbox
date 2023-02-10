<?php

declare(strict_types=1);

namespace Tests\Application\Actions\FileFinder\BusinessUnit;

use DPR\API\Application\Actions\ActionPayload;
use DPR\API\Domain\Models\BusinessUnit;
use DI\Container;
use DPR\API\Infrastructure\Persistence\DAO\DAOFactory;
use DPR\API\Infrastructure\Persistence\DAO\FileFinder\BusinessUnitDAO;
use PDOException;
use Tests\TestCase;

class CreateBusinessUnitActionTest extends TestCase
{

    public function createBusinessUnitCases()
    {
        $randomIds = range(1, 50);
        shuffle($randomIds);
        $randomIds = array_chunk($randomIds, 5)[rand(0, 4)];
        $titles = ["Operations", "Law Enforcement", "Warehouse", "Administrative", "Human Resources"];

        $data = array();
        for ($i = 0; $i < 5; $i++) {
            $businessUnit = new BusinessUnit(["bun_id" => $randomIds[$i], "bun_title" => $titles[$i], "bun_count" => 0, "bun_active" => 1]);
            $data[] = array(
                array(
                    "newTitle" => $titles[$i], "returnId" => $randomIds[$i],
                    "expectedObject" => $businessUnit
                )
            );
        }
        return $data;
    }

    /** 
     * @test 
     * @dataProvider createBusinessUnitCases
     */
    public function createBusinessUnit($testData)
    {
        $app = $this->getAppInstance();
        /** @var Container $container */
        $container = $app->getContainer();

        # Given a DB table for Business Units and a new Business Unit name
        $businessUnitDAOProphecy = $this->prophesize(BusinessUnitDAO::class);
        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);
        $daoFactoryProphecy
            ->createBusinessUnitDAO()
            ->willReturn($businessUnitDAOProphecy->reveal())
            ->shouldBeCalledOnce();

        $businessUnitDAOProphecy
            ->addNewBusinessUnit($testData["newTitle"])
            ->willReturn($testData["returnId"])
            ->shouldBeCalledOnce();

        $businessUnitDAOProphecy
            ->getById($testData["returnId"])
            ->willReturn($testData["expectedObject"])
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(BusinessUnitDAO::class, $businessUnitDAOProphecy->reveal());

        # When the POST api/filefinder/businessunits endpoint is reached with the new name
        $requestHeaders = [
            'HTTP_ACCEPT' => 'application/json',
        ];
        $request = $this->createRequest('POST', '/api/filefinder/businessunits', $requestHeaders);
        $request = $request->withParsedBody(array("title" => $testData["newTitle"]));
        $response = $app->handle($request);

        # The newly created Business Unit is returned
        $newTitle = $testData["newTitle"];
        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(200, [
            "message" => "Created new Business Unit '$newTitle'",
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

        $businessUnitDAOProphecy = $this->prophesize(BusinessUnitDAO::class);
        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);
        $daoFactoryProphecy
            ->createBusinessUnitDAO()
            ->willReturn($businessUnitDAOProphecy->reveal())
            ->shouldBeCalledOnce();

        # Given that the Business Unit DAO throws a PDOException
        $businessUnitDAOProphecy
            ->addNewBusinessUnit("New Business Unit")
            ->willThrow(new PDOException("Error 1169 SQLSTATE[23000]: Can't write, because of unique constraint, to table 'business_unit'"))
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(BusinessUnitDAO::class, $businessUnitDAOProphecy->reveal());

        # When the POST api/filefinder/businessunits endpoint is reached
        $request = $this->createRequest('POST', '/api/filefinder/businessunits');
        $request = $request->withParsedBody(["title" => "New Business Unit"]);
        $response = $app->handle($request);

        # The server responds with status code 500, and an error message
        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(
            500,
            ['message' => "Failed to create new Business Unit: Error 1169 SQLSTATE[23000]: Can't write, because of unique constraint, to table 'business_unit'"]
        );
        $serializedExpectedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);
        $this->assertEquals($serializedExpectedPayload, $payload);
    }
}
