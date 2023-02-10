<?php

declare(strict_types=1);

namespace Tests\Domain\Models;

use Tests\TestCase;
use DPR\API\Domain\Models\DocumentType;

class DocumentTypeTest extends TestCase
{
    public static function fileProvider()
    {
        $data = [
            [1, 'title 1', true],
            [2, 1, 'title 2', true]
        ];
        return $data;
    }

    /**
     * @dataProvider fileProvider
     * @param    $data
     */
    public function testGetters($id, $title, $active)
    {
        $documentTypeFromDatabase = new DocumentType(['dot_id' => $id, 'dot_title' => $title, 'dot_active' => $active]);
        $documentTypeFromFrontend = new DocumentType(json_encode(['id' => $id, 'title' => $title, 'active' => $active]));

        $this->assertEquals($id, $documentTypeFromDatabase->getId());
        $this->assertEquals($title, $documentTypeFromDatabase->getTitle());
        $this->assertEquals($active, $documentTypeFromDatabase->getActive());

        $this->assertEquals($id, $documentTypeFromFrontend->getId());
        $this->assertEquals($title, $documentTypeFromFrontend->getTitle());
        $this->assertEquals($active, $documentTypeFromFrontend->getActive());
    }
}
