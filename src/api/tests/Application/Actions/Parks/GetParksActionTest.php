<?php
declare(strict_types=1);

namespace Tests\Application\Actions\Parks;

use DPR\API\Application\Actions\ActionPayload;
use DPR\API\Infrastructure\Persistence\DAO\DAOFactory;
use DPR\API\Infrastructure\Persistence\DAO\ParkDAO;
use DPR\API\Domain\Models\Park;
use DI\Container;
use Tests\TestCase;
use PDOException;

class GetParksActionTest extends TestCase
{

    public function getAllParksCases()
    {
        return array(
            # 1 active park
            array(
                array("par_id" => 198, "par_code" => "ARCH", "par_reg_by" => NULL, "par_admin_by" => NULL, "par_name" => "At Raleigh Central Headquarters", "par_lat" => 35.78690000, "par_lon" => -78.63870000)
            ),
            # 3 active parks
            array(
                array("par_id" => 198, "par_code" => "ARCH", "par_reg_by" => NULL, "par_admin_by" => NULL, "par_name" => "At Raleigh Central Headquarters", "par_lat" => 35.78690000, "par_lon" => -78.63870000),
                array("par_id" => 199, "par_code" => "BAIS", "par_reg_by" => NULL, "par_admin_by" => 220, "par_name" => "Bald Head Island State Natural Area", "par_lat" => 33.86160000, "par_lon" => -77.96330000),
                array("par_id" => 200, "par_code" => "BALA", "par_reg_by" => NULL, "par_admin_by" => 233, "par_name" => "Bakers Lake State Natural Area", "par_lat" => 34.80960900, "par_lon" => -78.76468300)
            ),
            # No parks
            array(array())
        );
    }

    /** 
     * @test 
     * @dataProvider getAllParksCases
     */
    public function getAllParks($parkData)
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $parks = [];
        foreach ($parkData as $data) {
            $parks[] = new Park($data);
        }

        $parkDAOProphecy = $this->prophesize(ParkDAO::class);
        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);

        $daoFactoryProphecy
            ->createParkDAO()
            ->willReturn($parkDAOProphecy->reveal())
            ->shouldBeCalledOnce();

        $parkDAOProphecy
            ->getAllParks()
            ->willReturn($parks)
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(ParkDAO::class, $parkDAOProphecy->reveal());

        // Actual Results: Call the GET api/parks endpoint
        $request = $this->createRequest('GET', 'api/parks');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();

        // Expected Results: Retrieve correct original data
        $expectedPayload = new ActionPayload(200, ['parks' => $parks]);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }

    /** @test */
    public function willReturnServerError()
    {
        $app = $this->getAppInstance();
        /** @var Container $container */
        $container = $app->getContainer();

        $parkDAOProphecy = $this->prophesize(ParkDAO::class);
        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);

        $daoFactoryProphecy
            ->createParkDAO()
            ->willReturn($parkDAOProphecy->reveal())
            ->shouldBeCalledOnce();

        $parkDAOProphecy
            ->getAllParks()
            ->willReturn(new PDOException("Connection failed: SQLSTATE[HY000] [2002] Connection refused"))
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(ParkDAO::class, $parkDAOProphecy->reveal());

        // Actual Results: Call the GET api/parks endpoint
        $request = $this->createRequest('GET', 'api/parks');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();

        // Expected Results: Retrieve correct original data
        $expectedPayload = new ActionPayload(500, ['message' => "Could not retrieve list of Parks: Connection failed: SQLSTATE[HY000] [2002] Connection refused"]);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}
