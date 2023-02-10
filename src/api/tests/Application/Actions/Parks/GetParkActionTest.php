<?php
declare(strict_types=1);

namespace Tests\Application\Actions\Visitation\Parks;

use DPR\API\Application\Actions\ActionPayload;
use DPR\API\Infrastructure\Persistence\DAO\DAOFactory;
use DPR\API\Infrastructure\Persistence\DAO\ParkDAO;
use DPR\API\Domain\Models\Park;
use DI\Container;
use Tests\TestCase;
use PDOException;

class GetParkActionTest extends TestCase
{

    public function getParkCases()
    {
        return array(
            # 1 active park
            array(
                array("par_id" => 1, "par_code" => "ARCH", "par_reg_by" => NULL, "par_admin_by" => NULL, "par_name" => "At Raleigh Central Headquarters", "par_lat" => 35.78690000, "par_lon" => -78.63870000)
            ),
            # 1 active park
            array(
                array("par_id" => 1, "par_code" => "BALA", "par_reg_by" => NULL, "par_admin_by" => 233, "par_name" => "Bakers Lake State Natural Area", "par_lat" => 34.80960900, "par_lon" => -78.76468300)
            )
        );
    }

    /** 
     * @test 
     * @dataProvider getParkCases
     */
    public function getPark($parkData)
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $park = new Park($parkData);

        $parkDAOProphecy = $this->prophesize(ParkDAO::class);
        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);

        $daoFactoryProphecy
           ->createParkDAO()
           ->willReturn($parkDAOProphecy->reveal())
           ->shouldBeCalledOnce();

        $parkDAOProphecy
            ->getParkById(1)
            ->willReturn($park)
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(ParkDAO::class, $parkDAOProphecy->reveal());

        // Actual Results: Call the GET api/visitation/parks/1 endpoint
        $request = $this->createRequest('GET', 'api/visitation/parks/1');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();

        // Expected Results: Retrieve correct original data
        $expectedPayload = new ActionPayload(200, ['park' => $park]);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }

    /** 
     * @test
     */
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
            ->getParkById(1)
            ->willReturn(new PDOException("Connection failed: SQLSTATE[HY000] [2002] Connection refused"))
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(ParkDAO::class, $parkDAOProphecy->reveal());

        // Actual Results: Call the GET api/visitation/parks/1 endpoint
        $request = $this->createRequest('GET', 'api/visitation/parks/1');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();

        // Expected Results: Retrieve correct original data
        $expectedPayload = new ActionPayload(500, ['message' => "Could not retrieve list of Parks: Connection failed: SQLSTATE[HY000] [2002] Connection refused"]);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}
