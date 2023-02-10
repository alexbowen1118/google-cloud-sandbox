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

class GetDevicesActionTest extends TestCase
{

    public function getAllDevicesCases()
    {
        return array(
            # 1 active device
            array(
                array("dev_id" => 1, "dev_par_id" => 218, "dev_number" => 1, "dev_name" => "ENRI Counter 1", "dev_function" => 2, "dev_type" => 3, "dev_method" => 2, "dev_model" => 3, "dev_brand" => 2, "dev_multiplier" => 1.0, "dev_lat" => 36.39766000, "dev_lon" => -81.47346600, "dev_seeinsight_id" => "60ca4da51d8472746e1b7b75", "dev_date_uploaded" => "2022-07-01 00:00:00", "dev_status" => 1)
            ),
            # 1 inactive device
            array(
                array("dev_id" => 1, "dev_par_id" => 218, "dev_number" => 1, "dev_name" => "ENRI Counter 2", "dev_function" => 1, "dev_type" => 1, "dev_method" => 1, "dev_model" => 2, "dev_brand" => 2, "dev_multiplier" => 3.5, "dev_lat" => 36.07830000, "dev_lon" => -79.00500000, "dev_seeinsight_id" => "60ca4da51d8472746e1b7b34", "dev_date_uploaded" => "2022-06-02 00:00:00", "dev_status" => 0)
            ),
            # 3 active + nonactive devices
            array(
                array("dev_id" => 1, "dev_par_id" => 218, "dev_number" => 1, "dev_name" => "ENRI Counter 1", "dev_function" => 2, "dev_type" => 3, "dev_method" => 2, "dev_model" => 3, "dev_brand" => 2, "dev_multiplier" => 1.0, "dev_lat" => 36.39766000, "dev_lon" => -81.47346600, "dev_seeinsight_id" => "60ca4da51d8472746e1b7b75", "dev_date_uploaded" => "2022-07-01 00:00:00", "dev_status" => 1),
                array("dev_id" => 2, "dev_par_id" => 218, "dev_number" => 2, "dev_name" => "ENRI Counter 2", "dev_function" => 1, "dev_type" => 1, "dev_method" => 1, "dev_model" => 2, "dev_brand" => 2, "dev_multiplier" => 3.5, "dev_lat" => 36.07830000, "dev_lon" => -79.00500000, "dev_seeinsight_id" => "60ca4da51d8472746e1b7b34", "dev_date_uploaded" => "2022-06-02 00:00:00", "dev_status" => 0),
                array("dev_id" => 3, "dev_par_id" => 240, "dev_number" => 1, "dev_name" => "LEIS Traffic Counter", "dev_function" => 1, "dev_type" => 2, "dev_method" => 2, "dev_model" => 1, "dev_brand" => 1, "dev_multiplier" => 7.0, "dev_lat" => 34.32140000, "dev_lon" => -77.68780000, "dev_seeinsight_id" => "23ef4da33d8472746d1c7b12", "dev_date_uploaded" => "2021-02-15 00:00:00", "dev_status" => 1)
            ),
            # No devices
            array(array())
        );
    }

    /** 
     * @test 
     * @dataProvider getAllDevicesCases
     */
    public function getAllDevices($deviceData)
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $devices = [];
        foreach ($deviceData as $data) {
            $devices[] = new Device($data);
        }

        $deviceDAOProphecy = $this->prophesize(DeviceDAO::class);
        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);
        
        $daoFactoryProphecy
            ->createDeviceDAO()
            ->willReturn($deviceDAOProphecy->reveal())
            ->shouldBeCalledOnce();
        
        $deviceDAOProphecy
            ->getAllDevices()
            ->willReturn($devices)
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(DeviceDAO::class, $deviceDAOProphecy->reveal());

        // Actual Results: Call the GET api/visitation/devices endpoint
        $request = $this->createRequest('GET', 'api/visitation/devices');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();

        // Expected Results: Retrieve correct original data
        $expectedPayload = new ActionPayload(200, ['devices' => $devices]);
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
            ->getAllDevices()
            ->willReturn(new PDOException("Connection failed: SQLSTATE[HY000] [2002] Connection refused"))
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(DeviceDAO::class, $deviceDAOProphecy->reveal());

        // Actual Results: Call the GET api/visitation/devices endpoint
        $request = $this->createRequest('GET', 'api/visitation/devices');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();

        // Expected Results: Retrieve correct original data
        $expectedPayload = new ActionPayload(500, ['message' => "Could not retrieve list of Devices: Connection failed: SQLSTATE[HY000] [2002] Connection refused"]);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}
