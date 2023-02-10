<?php

declare(strict_types=1);

namespace Tests\Domain\Models;

use DPR\API\Domain\Models\Tag;
use Tests\TestCase;

class TagTest extends TestCase
{
    public static function tagProvider()
    {
        return [
            [1, "tag 1", true],
            [2, "tag 2", false]
        ];
    }

    /**
     * @dataProvider tagProvider
     * @param    $data
     */
    public function testGetters($tagId, $tagTitle, $tagActive)
    {
        $tagFromDatabase = new Tag(["tag_id" => $tagId, "tag_title" => $tagTitle, "tag_active" => $tagActive]);
        $tagFromFrontend = new Tag(json_encode(["id" => $tagId, "title" => $tagTitle, "active" => $tagActive]));


        $this->assertEquals($tagId, $tagFromDatabase->getId());
        $this->assertEquals($tagTitle, $tagFromDatabase->getTitle());
        $this->assertEquals($tagActive, $tagFromDatabase->getActive());

        $this->assertEquals($tagId, $tagFromFrontend->getId());
        $this->assertEquals($tagTitle, $tagFromFrontend->getTitle());
        $this->assertEquals($tagActive, $tagFromFrontend->getActive());
    }
}
