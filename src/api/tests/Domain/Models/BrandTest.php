<?php
declare(strict_types=1);

namespace Tests\Domain\Models;

use DPR\API\Domain\Models\Brand;
use Tests\TestCase;

class BrandTest extends TestCase {

    public static function brandProvider() {
        $data = [
            [1, "Particle", true]
        ];
        return $data;
    }

    /**
    * @dataProvider brandProvider
    * @param $id
    * @param $name
    * @param $status
    */
    public function testConstructor($id, $name, $status): void {
        // Test constructing with DB array
        $testBrandFromDB = new Brand(['brn_id' => $id, 'brn_name' => $name, 'brn_status' => $status]);
        // Test constructing with FE JSON
        $testBrandFromFE = new Brand(json_encode(['id' => $id, 'name' => $name, 'status' => $status]));

        $this->assertSame($id, $testBrandFromDB->getId());
        $this->assertSame($name, $testBrandFromDB->getName());
        $this->assertSame($status, $testBrandFromDB->getStatus());

        $this->assertSame($id, $testBrandFromFE->getId());
        $this->assertSame($name, $testBrandFromFE->getName());
        $this->assertSame($status, $testBrandFromFE->getStatus());
    }

}
