<?php

declare(strict_types=1);

namespace Tests\Application\Actions\FileFinder\BusinessUnit;

use DPR\API\Application\Actions\ActionPayload;
use DPR\API\Domain\Models\BusinessUnit;
use DI\Container;
use DPR\API\Infrastructure\Persistence\DAO\DAOFactory;
use DPR\API\Infrastructure\Persistence\DAO\FileFinder\BusinessUnitDAO;
use PDOException;
use Prophecy\Argument;
use Tests\TestCase;

class GetBusinessUnitsActionTest extends TestCase
{

    public function getAllBusinessUnitsCases()
    {
        return array(
            # 1 active BU
            array(
                array("bun_id" => 1, "bun_title" => "Law Enforcement", "bun_count" => 72, "bun_active" => 1),
            ),
            # 1 inactive BU
            array(
                array("bun_id" => 3, "bun_title" => "Other", "bun_count" => 3, "bun_active" => 0),
            ),
            # 5 BUs active + nonactive
            array(
                array("bun_id" => 1, "bun_title" => "Operations", "bun_count" => 23, "bun_active" => 1),
                array("bun_id" => 3, "bun_title" => "Warehouse", "bun_count" => 19, "bun_active" => 0),
                array("bun_id" => 7, "bun_title" => "Administrative", "bun_count" => 54, "bun_active" => 1),
                array("bun_id" => 9, "bun_title" => "Human Resources", "bun_count" => 68, "bun_active" => 1),
                array("bun_id" => 11, "bun_title" => "Major Maintenance", "bun_count" => 7, "bun_active" => 0)
            ),
            # No BUs
            array(array())
        );
    }

    /** 
     * @test 
     * @dataProvider getAllBusinessUnitsCases
     */
    public function getsAllBusinessUnits($businessUnitData)
    {
        $app = $this->getAppInstance();
        /** @var Container $container */
        $container = $app->getContainer();

        $businessUnits = [];
        foreach ($businessUnitData as $data) {
            $businessUnits[] = new BusinessUnit($data);
        }

        # Given a list of Business Units in the DB
        $businessUnitDAOProphecy = $this->prophesize(BusinessUnitDAO::class);
        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);
        $daoFactoryProphecy
            ->createBusinessUnitDAO()
            ->willReturn($businessUnitDAOProphecy->reveal())
            ->shouldBeCalledOnce();

        $businessUnitDAOProphecy
            ->getBusinessUnits(Argument::any())
            ->willReturn($businessUnits)
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(BusinessUnitDAO::class, $businessUnitDAOProphecy->reveal());

        # When the GET api/filefinder/businessunits endpoint is reached
        $request = $this->createRequest('GET', '/api/filefinder/businessunits');
        $response = $app->handle($request);

        # All the Business Units from the DB are retrieved
        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(200, ['businessunits' => $businessUnits]);
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
            ->getBusinessUnits(Argument::any())
            ->willThrow(new PDOException("Connection failed: SQLSTATE[HY000] [2002] Connection refused"))
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(BusinessUnitDAO::class, $businessUnitDAOProphecy->reveal());

        # When the GET api/filefinder/businessunits endpoint is reached
        $request = $this->createRequest('GET', '/api/filefinder/businessunits');
        $response = $app->handle($request);

        # The server responds with status code 500, and an error message
        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(500, ['message' => "Could not retrieve list of Business Units: Connection failed: SQLSTATE[HY000] [2002] Connection refused"]);
        $serializedExpectedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedExpectedPayload, $payload);
    }
}
