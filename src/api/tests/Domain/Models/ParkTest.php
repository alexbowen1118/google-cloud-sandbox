<?php
declare(strict_types=1);

namespace Tests\Domain\Models;

use DPR\API\Domain\Models\Park;
use Tests\TestCase;

class ParkTest extends TestCase {

    public static function parkProvider() {
        $data = [
            [199, 'BAIS', NULL, 220, 'Bald Head Island State Natural Area', 33.86160000, -77.96330000],
            [223, 'FRRI', NULL, 198, 'French Broad River State Trail', NULL, NULL],
            [245, 'MARI', NULL, NULL, 'Mayo River State Park', 36.43880000, -79.93817100]
        ];
        return $data;
    }

    /**
    * @dataProvider parkProvider
    * @param $id
    * @param $parkCode
    * @param $regionId
    * @param $adminBy
    * @param $name
    * @param $lat
    * @param $lon
    */
    public function testConstructor($id, $parkCode, $regionId, $adminBy, $name, $lat, $lon): void {
        // Test constructing with DB array
        $testParkFromDB = new Park(['par_id' => $id, 'par_code' => $parkCode, 'par_reg_id' => $regionId, 'par_admin_by' => $adminBy,
                'par_name' => $name, 'par_lat' => $lat, 'par_lon' => $lon]);
        // Test constructing with FE JSON
        $testParkFromFE = new Park(json_encode(['id' => $id, 'park_code' => $parkCode, 'region_id' => $regionId, 'admin_by' => $adminBy,
                'name' => $name, 'lat' => $lat, 'lon' => $lon]));

        $this->assertSame($id, $testParkFromDB->getId());
        $this->assertSame($parkCode, $testParkFromDB->getParkCode());
        $this->assertSame($regionId, $testParkFromDB->getRegionId());
        $this->assertSame($adminBy, $testParkFromDB->getAdminBy());
        $this->assertSame($name, $testParkFromDB->getName());
        $this->assertSame($lat, $testParkFromDB->getLat());
        $this->assertSame($lon, $testParkFromDB->getLon());

        $this->assertSame($id, $testParkFromFE->getId());
        $this->assertSame($parkCode, $testParkFromFE->getParkCode());
        $this->assertSame($regionId, $testParkFromFE->getRegionId());
        $this->assertSame($adminBy, $testParkFromFE->getAdminBy());
        $this->assertSame($name, $testParkFromFE->getName());
        $this->assertSame($lat, $testParkFromFE->getLat());
        $this->assertSame($lon, $testParkFromFE->getLon());
    }

}
