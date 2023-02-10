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

class BusinessUnitIncrementCountActionTest extends TestCase
{

    public function incrementCountCases()
    {
        $randomIds = range(1, 50);
        shuffle($randomIds);
        $randomIds = array_chunk($randomIds, 5)[rand(0, 4)];
        $titles = ["Operations", "Law Enforcement", "Warehouse", "Administrative", "Human Resources"];
        for ($i = 0; $i < 5; $i++) {
            $businessUnit = new BusinessUnit(["bun_id" => $randomIds[$i], "bun_title" => $titles[$i], "bun_count" => rand(50, 250), "bun_active" => rand(0, 1)]);
            $data[] = array(
                array("businessUnitObject" => $businessUnit)
            );
        }
        return $data;
    }

    /** 
     * @test 
     * @dataProvider incrementCountCases
     */
    public function incrementBusinessUnitCount($testData)
    {
        $app = $this->getAppInstance();
        /** @var Container $container */
        $container = $app->getContainer();

        $businessUnitObject = $testData["businessUnitObject"];
        $businessUnitId = (int) $businessUnitObject->id;

        # Given a DB table for Business Units and an id for a Business Unit
        $businessUnitDAOProphecy = $this->prophesize(BusinessUnitDAO::class);
        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);
        $daoFactoryProphecy
            ->createBusinessUnitDAO()
            ->willReturn($businessUnitDAOProphecy->reveal())
            ->shouldBeCalledOnce();

        $businessUnitDAOProphecy
            ->incrementCountForId($businessUnitId)
            ->willReturn($businessUnitObject)
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(BusinessUnitDAO::class, $businessUnitDAOProphecy->reveal());

        # When the PATCH api/filefinder/businessunits/{id}/active endpoint is reached with an id
        $request = $this->createRequest('PATCH', "/api/filefinder/businessunits/${businessUnitId}/count");
        $response = $app->handle($request);

        # A message with the updated usage status of the Business Unit is returned
        $expectedMessage = "Incremented usage count for Business Unit '$businessUnitObject->title' to '$businessUnitObject->count'";
        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(200, [
            "message" => $expectedMessage,
            "data" => $businessUnitObject
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

        $businessUnitDAOProphecy = $this->prophesize(BusinessUnitDAO::class);
        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);
        $daoFactoryProphecy
            ->createBusinessUnitDAO()
            ->willReturn($businessUnitDAOProphecy->reveal())
            ->shouldBeCalledOnce();

        # Given that the Business Unit DAO throws a PDOException
        $businessUnitDAOProphecy
            ->incrementCountForId($randId)
            ->willThrow(new PDOException("Error 1836 SQLSTATE[HY000] Running in read-only mode"))
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(BusinessUnitDAO::class, $businessUnitDAOProphecy->reveal());

        # When the PATCH api/filefinder/businessunits/{id}/active endpoint is reached
        $request = $this->createRequest('PATCH', "/api/filefinder/businessunits/$randId/count");
        $response = $app->handle($request);

        # The server responds with status code 500, and an error message
        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(
            500,
            ['message' => "Failed to increment usage count: Error 1836 SQLSTATE[HY000] Running in read-only mode"]
        );
        $serializedExpectedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);
        $this->assertEquals($serializedExpectedPayload, $payload);
    }
}
