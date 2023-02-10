<?php

declare(strict_types=1);

namespace DPR\API\Domain\Models;

use DPR\API\Domain\DomainModel;



/**
 * @todo Update constructor with database prefixes, and setters and getters if required
 */
class Topic extends DomainModel
{
    public $id;
    public $title;
    public $description;
    public $active;

    public function __construct($data)
    {
        $this->JSONFields = array("id", "title", "description");
        if (is_array($data)) { //Coming from the database
            $this->setId($data["top_id"]);
            $this->setTitle($data["top_title"]);
            $this->setDescription($data["top_description"]);
            $this->setActive($data["top_active"]);
        } elseif (is_string($data)) { //Coming from JSON
            $data = json_decode($data);
            if (isset($data->id)) $this->setId($data->id);
            if (isset($data->title)) $this->setTitle($data->title);
            if (isset($data->description)) $this->setDescription($data->description);
            if (isset($data->active)) $this->setActive($data->active);
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }


    public function getActive()
    {
        return $this->active;
    }

    public function setActive($active)
    {
        $this->active = $active;
    }
}
