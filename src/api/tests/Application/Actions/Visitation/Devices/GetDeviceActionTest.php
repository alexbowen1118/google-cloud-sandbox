<?php
declare(strict_types=1);

namespace Tests\Application\Actions\Visitation\Devices;

use DPR\API\Application\Actions\ActionPayload;
use DPR\API\Infrastructure\Persistence\DAO\DAOFactory;
use DPR\API\Infrastructure\Persistence\DAO\DeviceDAO;
use DPR\API\Domain\Models\Device;
use DI\Container;
use Tests\TestCase;
use PDOException;

class GetDeviceActionTest extends TestCase
{

    public function getDeviceCases()
    {
        return array(
            # 1 active park
            array(
                array("dev_id" => 1, "dev_par_id" => 218, "dev_number" => 1, "dev_name" => "ENRI Counter 1", "dev_function" => 2, "dev_type" => 3, "dev_method" => 2, "dev_model" => 3, "dev_brand" => 2, "dev_multiplier" => 1.0, "dev_lat" => 36.39766000, "dev_lon" => -81.47346600, "dev_seeinsight_id" => "60ca4da51d8472746e1b7b75", "dev_date_uploaded" => "2022-07-01 00:00:00", "dev_status" => 1)
            ),
            # 1 active park
            array(
                array("dev_id" => 1, "dev_par_id" => 218, "dev_number" => 1, "dev_name" => "ENRI Counter 2", "dev_function" => 1, "dev_type" => 1, "dev_method" => 1, "dev_model" => 2, "dev_brand" => 2, "dev_multiplier" => 3.5, "dev_lat" => 36.07830000, "dev_lon" => -79.00500000, "dev_seeinsight_id" => "60ca4da51d8472746e1b7b34", "dev_date_uploaded" => "2022-06-02 00:00:00", "dev_status" => 1)
            )
        );
    }

    /** 
     * @test 
     * @dataProvider getDeviceCases
     */
    public function getDevice($deviceData)
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $device = new Device($deviceData);

        $deviceDAOProphecy = $this->prophesize(DeviceDAO::class);
        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);

        $daoFactoryProphecy
           ->createDeviceDAO()
           ->willReturn($deviceDAOProphecy->reveal())
           ->shouldBeCalledOnce();

        $deviceDAOProphecy
            ->getDeviceById(1)
            ->willReturn($device)
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(DeviceDAO::class, $deviceDAOProphecy->reveal());

        // Actual Results: Call the GET api/visitation/devices/1 endpoint
        $request = $this->createRequest('GET', 'api/visitation/devices/1');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();

        // Expected Results: Retrieve correct original data
        $expectedPayload = new ActionPayload(200, ['device' => $device]);
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

        $deviceDAOProphecy = $this->prophesize(DeviceDAO::class);
        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);

        $daoFactoryProphecy
           ->createDeviceDAO()
           ->willReturn($deviceDAOProphecy->reveal())
           ->shouldBeCalledOnce();

        $deviceDAOProphecy
            ->getDeviceById(1)
            ->willReturn(new PDOException("Connection failed: SQLSTATE[HY000] [2002] Connection refused"))
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(DeviceDAO::class, $deviceDAOProphecy->reveal());

        // Actual Results: Call the GET api/visitation/devices/1 endpoint
        $request = $this->createRequest('GET', 'api/visitation/devices/1');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();

        // Expected Results: Retrieve correct original data
        $expectedPayload = new ActionPayload(500, ['message' => "Could not retrieve list of Devices: Connection failed: SQLSTATE[HY000] [2002] Connection refused"]);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}
