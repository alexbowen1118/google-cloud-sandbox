<?php
declare(strict_types=1);

namespace Tests\Domain\Models;

use DPR\API\Domain\Models\Method;
use Tests\TestCase;

class MethodTest extends TestCase {

    public static function methodProvider() {
        $data = [
            [1, "Manual", false],
            [2, "Automatic", true]
        ];
        return $data;
    }

    /**
    * @dataProvider methodProvider
    * @param $id
    * @param $name
    * @param $status
    */
    public function testConstructor($id, $name, $status): void {
        // Test constructing with DB array
        $testMethodFromDB = new Method(['mtd_id' => $id, 'mtd_name' => $name, 'mtd_status' => $status]);
        // Test constructing with FE JSON
        $testMethodFromFE = new Method(json_encode(['id' => $id, 'name' => $name, 'status' => $status]));

        $this->assertSame($id, $testMethodFromDB->getId());
        $this->assertSame($name, $testMethodFromDB->getName());
        $this->assertSame($status, $testMethodFromDB->getStatus());

        $this->assertSame($id, $testMethodFromFE->getId());
        $this->assertSame($name, $testMethodFromFE->getName());
        $this->assertSame($status, $testMethodFromFE->getStatus());
    }

}
