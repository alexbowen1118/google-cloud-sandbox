<?php
declare(strict_types=1);

namespace Tests\Domain\Models;

use DPR\API\Domain\Models\Model;
use Tests\TestCase;

class ModelTest extends TestCase {

    public static function modelProvider() {
        $data = [
            [1, "model", true]
        ];
        return $data;
    }

    /**
    * @dataProvider modelProvider
    * @param $id
    * @param $name
    * @param $status
    */
    public function testConstructor($id, $name, $status): void {
        // Test constructing with DB array
        $testModelFromDB = new Model(['mdl_id' => $id, 'mdl_name' => $name, 'mdl_status' => $status]);
        // Test constructing with FE JSON
        $testModelFromFE = new Model(json_encode(['id' => $id, 'name' => $name, 'status' => $status]));

        $this->assertSame($id, $testModelFromDB->getId());
        $this->assertSame($name, $testModelFromDB->getName());
        $this->assertSame($status, $testModelFromDB->getStatus());

        $this->assertSame($id, $testModelFromFE->getId());
        $this->assertSame($name, $testModelFromFE->getName());
        $this->assertSame($status, $testModelFromFE->getStatus());
    }

}
