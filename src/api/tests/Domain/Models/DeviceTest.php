<?php
declare(strict_types=1);

namespace Tests\Domain\Models;

use DPR\API\Domain\Models\Device;
use Tests\TestCase;

class DeviceTest extends TestCase {

    public static function deviceProvider() {
        $data = [
            [1, 2, 1, "name", 2, 3, 1, 1, 2, 2.5, 36.39766000, -81.47346600, "60ca4da51d8472746e1b7b75", 1, "2022-07-01 00:00:00"]
        ];
        return $data;
    }

    /**
    * @dataProvider deviceProvider
    * @param $id
    * @param $par_id
    * @param $number
    * @param $name
    * @param $function
    * @param $type
    * @param $method
    * @param $model
    * @param $brand
    * @param $multiplier
    * @param $lat
    * @param $lon
    * @param $seeinsight_id
    * @param $status
    * @param $date_uploaded
    */
    public function testConstructor($id, $par_id, $number, $name, $function, $type, $method, $model, $brand, $multiplier,
            $lat, $lon, $seeinsight_id, $date_uploaded, $status): void {
        // Test constructing with DB array
        $testDeviceFromDB = new Device(['dev_id' => $id, 'dev_par_id' => $par_id, 'dev_number' => $number, 'dev_name' => $name,
                'dev_function' => $function, 'dev_type' => $type, 'dev_method' => $method, 'dev_model' => $model,
                'dev_brand' => $brand, 'dev_multiplier' => $multiplier, 'dev_lat' => $lat, 'dev_lon' => $lon,
                'dev_seeinsight_id' => $seeinsight_id, 'dev_date_uploaded' => $date_uploaded, 'dev_status' => $status]);
        // Test constructing with FE JSON
        $testDeviceFromFE = new Device(json_encode(['id' => $id, 'par_id' => $par_id, 'number' => $number, 'name' => $name,
                'function' => $function, 'type' => $type, 'method' => $method, 'model' => $model, 'brand' => $brand,
                'multiplier' => $multiplier, 'lat' => $lat, 'lon' => $lon, 'seeinsight_id' => $seeinsight_id,
                'date_uploaded' => $date_uploaded, 'status' => $status]));

        $this->assertSame($id, $testDeviceFromDB->getId());
        $this->assertSame($par_id, $testDeviceFromDB->getParkId());
        $this->assertSame($number, $testDeviceFromDB->getNumber());
        $this->assertSame($name, $testDeviceFromDB->getName());
        $this->assertSame($function, $testDeviceFromDB->getFunction());
        $this->assertSame($type, $testDeviceFromDB->getType());
        $this->assertSame($method, $testDeviceFromDB->getMethod());
        $this->assertSame($model, $testDeviceFromDB->getModel());
        $this->assertSame($brand, $testDeviceFromDB->getBrand());
        $this->assertSame($multiplier, $testDeviceFromDB->getMultiplier());
        $this->assertSame($lat, $testDeviceFromDB->getLat());
        $this->assertSame($lon, $testDeviceFromDB->getLon());
        $this->assertSame($seeinsight_id, $testDeviceFromDB->getSeeInsightId());
        $this->assertSame($date_uploaded, $testDeviceFromDB->getDateUploaded());
        $this->assertSame($status, $testDeviceFromDB->getStatus());

        $this->assertSame($id, $testDeviceFromFE->getId());
        $this->assertSame($par_id, $testDeviceFromFE->getParkId());
        $this->assertSame($number, $testDeviceFromFE->getNumber());
        $this->assertSame($name, $testDeviceFromFE->getName());
        $this->assertSame($function, $testDeviceFromFE->getFunction());
        $this->assertSame($type, $testDeviceFromFE->getType());
        $this->assertSame($method, $testDeviceFromFE->getMethod());
        $this->assertSame($model, $testDeviceFromFE->getModel());
        $this->assertSame($brand, $testDeviceFromFE->getBrand());
        $this->assertSame($multiplier, $testDeviceFromFE->getMultiplier());
        $this->assertSame($lat, $testDeviceFromFE->getLat());
        $this->assertSame($lon, $testDeviceFromFE->getLon());
        $this->assertSame($seeinsight_id, $testDeviceFromFE->getSeeInsightId());
        $this->assertSame($date_uploaded, $testDeviceFromFE->getDateUploaded());
        $this->assertSame($status, $testDeviceFromFE->getStatus());
    }

}
