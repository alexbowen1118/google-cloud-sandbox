<?php

declare(strict_types=1);

namespace DPR\API\Domain\Models;

use DPR\API\Domain\DomainModel;

class BusinessUnit extends DomainModel
{

    public $id;
    public $title;
    public $count;
    public $active;

    function __construct($data)
    {
        $this->JSONFields = array("id", "title", "count");
        if (is_array($data)) { //Coming from the database
            $this->setId($data["bun_id"]);
            $this->setTitle($data["bun_title"]);
            $this->setCount($data["bun_count"]);
            $this->setActive($data["bun_active"]);
        } elseif (is_string($data)) { //Coming from JSON
            $data = json_decode($data);
            if (isset($data->id)) $this->setId($data->id);
            if (isset($data->title)) $this->setTitle($data->title);
            if (isset($data->count)) $this->setCount($data->count);
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

    public function getCount()
    {
        return $this->count;
    }

    public function setCount($count)
    {
        $this->count = $count;
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
