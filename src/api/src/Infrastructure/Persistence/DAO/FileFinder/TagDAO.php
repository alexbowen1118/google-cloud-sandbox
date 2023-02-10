<?php

declare(strict_types=1);

namespace DPR\API\Infrastructure\Persistence\DAO\FileFinder;

use DPR\API\Infrastructure\Persistence\DAO\DAO;

use DPR\API\Domain\Models\Tag;

class TagDAO extends DAO
{

    protected $BaseQuery = "SELECT * FROM ncparks.tag ";
    protected $InsertQuery = "INSERT INTO ncparks.tag (tag_title) VALUES (:title)";

    /**
     * Gets all tags
     * @param bool - Active status of tags to get
     * @return Tag[] - array of Tag objects
     */
    function getTags($data)
    {
        $DBConn = $this->DBPool->request();
        $allTags = [];
        if (key_exists("active", $data)) {
            $inactiveTags = [];
            if (in_array("0", $data["active"])) {
                // Retrieve inactive tags
                $retrieveInactiveTagsQuery = $this->BaseQuery . " WHERE tag_active = :inactive_tags";
                $valueForInactiveTagsQuery = ["inactive_tags" => 0];
                $DBConn->queryWithNamedData($retrieveInactiveTagsQuery, $valueForInactiveTagsQuery);
                while ($DBConn->nextRow()) {
                    $inactiveTags[] = new Tag($DBConn->getRow());
                }
                $allTags["inactive"] = $inactiveTags;
            }
            $activeTags = [];
            if (in_array("1", $data["active"])) {
                // Retrieve active tags
                $retrieveActiveTagsQuery = $this->BaseQuery . " WHERE tag_active = :active_tags";
                $valueForActiveTagsQuery = ["active_tags" => 1];
                $DBConn->queryWithNamedData($retrieveActiveTagsQuery, $valueForActiveTagsQuery);

                while ($DBConn->nextRow()) {
                    $activeTags[] = new Tag($DBConn->getRow());
                }
                $allTags["active"] = $activeTags;
            }
        } else {
            $DBConn->query($this->BaseQuery);
            while ($DBConn->nextRow()) {
                $allTags[] = new Tag($DBConn->getRow());
            }
        }

        $this->DBPool->release($DBConn);
        return $allTags;
    }

    /** 
     * Gets the tag with the given id
     * @param int $id for the tag
     * @return Tag object, or null
     */
    function getById($id)
    {
        $DBConn = $this->DBPool->request();
        $data = [
            "id" => $id
        ];
        $DBConn->queryWithNamedData(
            $this->BaseQuery . " WHERE tag_id = :id",
            $data
        );
        $Tag = null;
        if ($DBConn->nextRow()) {
            $Tag = new Tag($DBConn->getRow());
        }
        $this->DBPool->release($DBConn);
        return $Tag;
    }

    /**
     * Gets a list of tags for a given file id
     * @param int $id for the file
     * @return List<int> object, or null
     */
    function getByFileId($id)
    {
        $DBConn = $this->DBPool->request();
        $data = [
            "id" => $id
        ];
        $DBConn->queryWithNamedData(
            "SELECT flt_tag_id FROM ncparks.file_tag WHERE flt_fil_id = :id ",
            $data
        );
        $tags = [];
        while ($DBConn->nextRow()) {
            array_push($tags, $DBConn->getRow()["flt_tag_id"]);
        }
        $this->DBPool->release($DBConn);
        return $tags;
    }

    /**
     * Gets all tags with the given status
     * @param int $active denotes if the tag is actively used in FileFinder
     * @return Tag[] - array of Tag objects
     */
    function getByStatus($active)
    {
        $DBConn = $this->DBPool->request();
        $data = [
            "active" => $active
        ];
        $DBConn->queryWithNamedData(
            $this->BaseQuery . " WHERE tag_active = :active",
            $data
        );
        $Tags = [];
        while ($DBConn->nextRow()) {
            $Tags[] = new Tag($DBConn->getRow());
        }
        $this->DBPool->release($DBConn);
        return $Tags;
    }

    /**
     * Switches the active status for the specified (by id) tag
     * @param int $id for the tag
     * @return Tag that was updated
     */
    function switchActiveStatusForId($id)
    {
        $DBConn = $this->DBPool->request();
        $DBConn->queryWithNamedData(
            "UPDATE ncparks.tag SET tag_active = 1 - tag_active WHERE tag_id = :id",
            array("id" => $id)
        );
        # fetch updated
        $DBConn->queryWithNamedData(
            $this->BaseQuery . " WHERE tag_id = :id",
            array("id" => $id)
        );
        $Tag = null;
        if ($DBConn->nextRow()) {
            $Tag = new Tag($DBConn->getRow());
        }
        $this->DBPool->release($DBConn);
        return $Tag;
    }

    /**
     * Adds a new entry in the tag table
     * @param string $title for the new tag
     * @return int id for the new tag added to the DB
     */
    function addNewTag($title)
    {
        $DBConn = $this->DBPool->request();
        $DBConn->queryWithNamedData(
            $this->InsertQuery,
            array("title" => $title)
        );
        $newTagId = $DBConn->lastInsertID();
        $this->DBPool->release($DBConn);
        return $newTagId;
    }
}
