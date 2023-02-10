<?php
declare(strict_types=1);
namespace DPR\API\Domain\Models;

use DPR\API\Domain\DomainModel;

class CounterRule extends DomainModel {

    public $id;
    public $dev_id;
    public $start;
    public $end;
    public $multiplier;
    public $status;

    function __construct($data) {
        $this->JSONFields = array('id', 'dev_id', 'start', 'end', 'multiplier', 'status');
        if(is_array($data)) { //Coming from the database
            $this->setId($data['rul_id']);
            $this->setDev_id($data['rul_dev_id']);
            $this->setStart($data['rul_start']);
            $this->setEnd($data['rul_end']);
            $this->setMultiplier($data['rul_multiplier']);
            $this->setStatus($data['rul_status']);
        }
        elseif(is_string($data)) { //Coming from JSON
            $data = json_decode($data);
            if(isset($data->id)) $this->setId($data->id);
            if(isset($data->dev_id)) $this->setDev_id($data->dev_id);
            if(isset($data->start)) $this->setStart($data->start);
            if(isset($data->end)) $this->setEnd($data->end);
            if(isset($data->multiplier)) $this->setMultiplier($data->multiplier);
            if(isset($data->status)) $this->setStatus($data->status);
        }
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getDevId() {
        return $this->dev_id;
    }

    public function setDev_id($dev_id) {
        $this->dev_id = $dev_id;
    }

    public function getStart() {
        return $this->start;
    }

    public function setStart($start) {
        $this->start = $start;
    }

    public function getEnd() {
        return $this->end;
    }

    public function setEnd($end) {
        $this->end = $end;
    }

    public function getMultiplier() {
        return $this->multiplier;
    }

    public function setMultiplier($multiplier) {
        $this->multiplier = $multiplier;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

}
