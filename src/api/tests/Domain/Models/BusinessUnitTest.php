<?php

declare(strict_types=1);

namespace Tests\Domain\Models;

use Tests\TestCase;
use DPR\API\Domain\Models\BusinessUnit;

class BusinessUnitTest extends TestCase
{
    public static function fileProvider()
    {
        $data = [
            [1, 'title 1', 5, true],
            [2, 1, 'title 2', 8, true]
        ];
        return $data;
    }

    /**
     * @dataProvider fileProvider
     * @param    $data
     */
    public function testGetters($id, $title, $count, $active)
    {
        $businessUnitFromDatabase = new BusinessUnit(['bun_id' => $id, 'bun_title' => $title, 'bun_count' => $count, 'bun_active' => $active]);
        $businessUnitFromFrontend = new BusinessUnit(json_encode(['id' => $id, 'title' => $title, 'count' => $count, 'active' => $active]));

        $this->assertEquals($id, $businessUnitFromDatabase->getId());
        $this->assertEquals($title, $businessUnitFromDatabase->getTitle());
        $this->assertEquals($count, $businessUnitFromDatabase->getCount());
        $this->assertEquals($active, $businessUnitFromDatabase->getActive());

        $this->assertEquals($id, $businessUnitFromFrontend->getId());
        $this->assertEquals($title, $businessUnitFromFrontend->getTitle());
        $this->assertEquals($count, $businessUnitFromDatabase->getCount());
        $this->assertEquals($active, $businessUnitFromFrontend->getActive());
    }
}
