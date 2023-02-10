<?php
declare(strict_types=1);

namespace Tests\Domain\Models;

use DPR\API\Domain\Models\DeviceFunction;
use Tests\TestCase;

class DeviceFunctionTest extends TestCase {

    public static function functionProvider() {
        $data = [
            [1, "Traffic", true],
            [2, "Trail", true],
            [3, "Visitor Center", true]
        ];
        return $data;
    }

    /**
    * @dataProvider functionProvider
    * @param $id
    * @param $name
    * @param $status
    */
    public function testConstructor($id, $name, $status): void {
        // Test constructing with DB array
        $testFunctionFromDB = new DeviceFunction(['fnc_id' => $id, 'fnc_name' => $name, 'fnc_status' => $status]);
        // Test constructing with FE JSON
        $testFunctionFromFE = new DeviceFunction(json_encode(['id' => $id, 'name' => $name, 'status' => $status]));

        $this->assertSame($id, $testFunctionFromDB->getId());
        $this->assertSame($name, $testFunctionFromDB->getName());
        $this->assertSame($status, $testFunctionFromDB->getStatus());

        $this->assertSame($id, $testFunctionFromFE->getId());
        $this->assertSame($name, $testFunctionFromFE->getName());
        $this->assertSame($status, $testFunctionFromFE->getStatus());
    }

}
