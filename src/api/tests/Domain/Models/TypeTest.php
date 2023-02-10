<?php
declare(strict_types=1);

namespace Tests\Domain\Models;

use DPR\API\Domain\Models\Type;
use Tests\TestCase;

class TypeTest extends TestCase {

    public static function typeProvider() {
        $data = [
            [1, "Pneumatic", true],
            [2, "Inductive-Loop", false],
            [3, "Infra-Red", true],
            [4, "Cellular", true]
        ];
        return $data;
    }

    /**
    * @dataProvider typeProvider
    * @param $id
    * @param $name
    * @param $status
    */
    public function testConstructor($id, $name, $status): void {
        // Test constructing with DB array
        $testTypeFromDB = new Type(['typ_id' => $id, 'typ_name' => $name, 'typ_status' => $status]);
        // Test constructing with FE JSON
        $testTypeFromFE = new Type(json_encode(['id' => $id, 'name' => $name, 'status' => $status]));

        $this->assertSame($id, $testTypeFromDB->getId());
        $this->assertSame($name, $testTypeFromDB->getName());
        $this->assertSame($status, $testTypeFromDB->getStatus());

        $this->assertSame($id, $testTypeFromFE->getId());
        $this->assertSame($name, $testTypeFromFE->getName());
        $this->assertSame($status, $testTypeFromFE->getStatus());
    }

}
