<?php

declare(strict_types=1);

namespace Tests\Domain\Models;

use Tests\TestCase;
use DPR\API\Domain\Models\Topic;

class TopicTest extends TestCase
{
    public static function fileProvider()
    {
        $data = [
            [1, 'title 1', 'description 1', true],
            [2, 1, 'title 2',  'description 2', true]
        ];
        return $data;
    }

    /**
     * @dataProvider fileProvider
     * @param    $data
     */
    public function testGetters($id, $title, $description, $active)
    {
        $topicFromDatabase = new Topic(['top_id' => $id, 'top_title' => $title, 'top_description' => $description, 'top_active' => $active]);
        $topicFromFrontend = new Topic(json_encode(['id' => $id, 'title' => $title, 'description' => $description , 'active' => $active]));

        $this->assertEquals($id, $topicFromDatabase->getId());
        $this->assertEquals($title, $topicFromDatabase->getTitle());
        $this->assertEquals($description, $topicFromDatabase->getDescription());
        $this->assertEquals($active, $topicFromDatabase->getActive());

        $this->assertEquals($id, $topicFromFrontend->getId());
        $this->assertEquals($title, $topicFromFrontend->getTitle());
        $this->assertEquals($description, $topicFromFrontend->getDescription());
        $this->assertEquals($active, $topicFromFrontend->getActive());
    }
}
