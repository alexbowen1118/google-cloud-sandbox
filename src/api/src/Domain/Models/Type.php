<?php
declare(strict_types=1);
namespace DPR\API\Domain\Models;

use DPR\API\Domain\DomainModel;

class Type extends DomainModel {

    public $id;
    public $name;
    public $status;

    function __construct($data) {
        $this->JSONFields = array('id', 'name', 'status');
        if(is_array($data)) { //Coming from the database
            $this->setId($data['typ_id']);
            $this->setName($data['typ_name']);
            $this->setStatus($data['typ_status']);
        }
        elseif(is_string($data)) { //Coming from JSON
            $data = json_decode($data);
            if(isset($data->id)) $this->setId($data->id);
            if(isset($data->name)) $this->setName($data->name);
            if(isset($data->status)) $this->setStatus($data->status);
        }
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

}
