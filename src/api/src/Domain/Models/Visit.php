<?php
declare(strict_types=1);
namespace DPR\API\Domain\Models;

use DPR\API\Domain\DomainModel;

class Visit extends DomainModel {

    public $id;
    public $par_id;
    public $dev_id;
    public $timestamp;
    public $count;
    public $count_calculated;
    public $comments;
    public $status;

    function __construct($data) {
        $this->JSONFields = array('id', 'par_id', 'dev_id', 'timestamp', 'count', 'count_calculated', 'comments', 'status');
        if(is_array($data)) { //Coming from the database
            $this->setId($data['vis_id']);
            $this->setParId($data['vis_par_id']);
            $this->setDevId($data['vis_dev_id']);
            $this->setTimestamp($data['vis_timestamp']);
            $this->setCount($data['vis_count']);
            $this->setCountCalculated($data['vis_count_calculated']);
            $this->setComments($data['vis_comments']);
            $this->setStatus($data['vis_status']);
        }
        elseif(is_string($data)) { //Coming from JSON
            $data = json_decode($data);
            if(isset($data->id)) $this->setId($data->id);
            if(isset($data->par_id)) $this->setParId($data->par_id);
            if(isset($data->dev_id)) $this->setDevId($data->dev_id);
            if(isset($data->timestamp)) $this->setTimestamp($data->timestamp);
            if(isset($data->count)) $this->setCount($data->count);
            if(isset($data->count_calculated)) $this->setCountCalculated($data->count_calculated);
            if(isset($data->comments)) $this->setComments($data->comments);
            if(isset($data->status)) $this->setStatus($data->status);
        }
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getParId() {
        return $this->par_id;
    }

    public function setParId($par_id) {
        $this->par_id = $par_id;
    }

    public function getDevId() {
        return $this->dev_id;
    }

    public function setDevId($devId) {
        $this->dev_id = $devId;
    }

    public function getTimestamp() {
        return $this->timestamp;
    }

    public function setTimestamp($timestamp) {
        $this->timestamp = $timestamp;
    }

    public function getCount() {
        return $this->count;
    }

    public function setCount($count) {
        $this->count = $count;
    }

    public function getCountCalculated() {
        return $this->count_calculated;
    }

    public function setCountCalculated($count_calculated) {
        $this->count_calculated = $count_calculated;
    }

    public function getComments() {
        return $this->comments;
    }

    public function setComments($comments) {
        $this->comments = $comments;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

}
