<?php

declare(strict_types=1);

namespace DPR\API\Domain\Models;

use DPR\API\Domain\DomainModel;

class Tag extends DomainModel
{

    public $id;
    public $title;
    public $active;

    function __construct($data)
    {
        $this->JSONFields = array("id", "title");
        if (is_array($data)) { //Coming from the database
            $this->setId($data["tag_id"]);
            $this->setTitle($data["tag_title"]);
            $this->setActive($data["tag_active"]);
        } elseif (is_string($data)) { //Coming from JSON
            $data = json_decode($data);
            if (isset($data->id)) $this->setId($data->id);
            if (isset($data->title)) $this->setTitle($data->title);
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

    public function getActive()
    {
        return $this->active;
    }

    public function setActive($active)
    {
        $this->active = $active;
    }
}
