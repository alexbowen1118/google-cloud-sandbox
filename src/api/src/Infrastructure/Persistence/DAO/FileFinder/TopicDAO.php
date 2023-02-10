<?php

declare(strict_types=1);

namespace DPR\API\Infrastructure\Persistence\DAO\FileFinder;

use DPR\API\Infrastructure\Persistence\DAO\DAO;

use DPR\API\Domain\Models\Topic;

class TopicDAO extends DAO
{
    protected $BaseQuery = "SELECT * FROM ncparks.topic ";
    protected $updateBaseQuery = "UPDATE ncparks.topic SET ";

    /**
     * Retrieves topic by id
     *
     * @param Topic $id for the topic 
     * @return void
     */
    function getById($id)
    {
        $DBConn = $this->DBPool->request();
        $DBConn->queryWithNamedData($this->BaseQuery . " WHERE top_id = :id", ["id" => $id]);
        $topic = null;
        if ($DBConn->nextRow()) {
            $topic = new Topic($DBConn->getRow());
        }
        $this->DBPool->release($DBConn);
        return $topic;
    }

    /**
     * Gets all topics
     *
     * @return Topic[] - array of topic objects
     */
    function getTopics()
    {
        $DBConn = $this->DBPool->request();
        $DBConn->query($this->BaseQuery);
        $topics = [];
        while ($DBConn->nextRow()) {
            $topics[] = new Topic($DBConn->getRow());
        }
        $this->DBPool->release($DBConn);
        return $topics;
    }

    /**
     * Inserts a new topic in the topic table
     *
     * @param string $title for the topic
     * @param string $description attached to the topic
     * @return Topic Newly created topic 
     */
    function createTopic($title, $description)
    {
        $DBConn = $this->DBPool->request();
        $data = [
            "top_title" => $title,
            "top_description" => $description
        ];
        $DBConn->queryWithNamedData(
            "INSERT INTO ncparks.topic (top_title, top_description) VALUES (:top_title, :top_description)",
            $data
        );

        # fetch newly created topic
        $topic = $this->getById($DBConn->lastInsertID());
        $this->DBPool->release($DBConn);
        return $topic;
    }

    /**
     * Updates a topic title and description
     *
     * @param string $title for the topic
     * @param string $description attached to the topic
     * @return void
     */
    function editTopicTitleAndDescription($id, $title, $description)
    {
        if (!empty($title) || !empty($description)) {
            $DBConn = $this->DBPool->request();
            $editTopicQuery = $this->updateBaseQuery;
            $data = [];
            $data["top_id"] = $id;
            if (!empty($title)) {
                $data["top_title"] = $title;
                $editTopicQuery .= "top_title = :top_title";
            }
            if (!empty($description)) {
                $data["top_description"] = $description;
                if (!empty($title)) {
                    $editTopicQuery .= ", ";
                }
                $editTopicQuery .= "top_description = :top_description";
            }

            $editTopicQuery .= " WHERE top_id = :top_id";

            $DBConn->queryWithNamedData($editTopicQuery, $data);
            $this->DBPool->release($DBConn);
        }

        # fetch (possibly) updated topic
        $topic = $this->getById($id);
        return $topic;
    }

    /**
     * Switches the active status for the specified (by id) topic
     * @param int $id for the topic
     * @return Topic that was updated
     */
    function switchActiveStatusForId($id)
    {
        $DBConn = $this->DBPool->request();
        $DBConn->queryWithNamedData(
            "UPDATE ncparks.topic SET top_active = 1 - top_active WHERE top_id = :top_id",
            array("id" => $id)
        );

        # fetch updated topic
        $Topic = $this->getById($id);
        $this->DBPool->release($DBConn);
        return $Topic;
    }
}
