<?php
declare(strict_types=1);
namespace DPR\API\Domain\Models;

use DPR\API\Domain\DomainModel;

class Device extends DomainModel {

    public $id;
    public $par_id;
    public $number;
    public $name;
    public $function;
    public $type;
    public $method;
    public $model;
    public $brand;
    public $multiplier;
    public $lat;
    public $lon;
    public $seeinsight_id;
    public $status;
    public $date_uploaded;

    function __construct($data) {
        $this->JSONFields = array('id', 'par_id', 'number', 'name', 'function', 'type', 'method', 'model', 'brand',
                'multiplier', 'lat', 'lon', 'seeinsight_id', 'status', 'date_uploaded');
        if(is_array($data)) { //Coming from the database
            $this->setId($data['dev_id']);
            $this->setParkId($data['dev_par_id']);
            $this->setNumber($data['dev_number']);
            $this->setName($data['dev_name']);
            $this->setFunction($data['dev_function']);
            $this->setType($data['dev_type']);
            $this->setMethod($data['dev_method']);
            $this->setModel($data['dev_model']);
            $this->setBrand($data['dev_brand']);
            $this->setMultiplier($data['dev_multiplier']);
            $this->setLat($data['dev_lat']);
            $this->setLon($data['dev_lon']);
            $this->setSeeInsightId($data['dev_seeinsight_id']);
            $this->setStatus($data['dev_status']);
            $this->setDateUploaded($data['dev_date_uploaded']);
        }
        elseif(is_string($data)) { //Coming from JSON
            $data = json_decode($data);
            if(isset($data->id)) $this->setId($data->id);
            if(isset($data->par_id)) $this->setParkId($data->par_id);
            if(isset($data->number)) $this->setNumber($data->number);
            if(isset($data->name)) $this->setName($data->name);
            if(isset($data->function)) $this->setFunction($data->function);
            if(isset($data->type)) $this->setType($data->type);
            if(isset($data->method)) $this->setMethod($data->method);
            if(isset($data->model)) $this->setModel($data->model);
            if(isset($data->brand)) $this->setBrand($data->brand);
            if(isset($data->multiplier)) $this->setMultiplier($data->multiplier);
            if(isset($data->lat)) $this->setLat($data->lat);
            if(isset($data->lon)) $this->setLon($data->lon);
            if(isset($data->seeinsight_id)) $this->setSeeInsightId($data->seeinsight_id);
            if(isset($data->status)) $this->setStatus($data->status);
            if(isset($data->date_uploaded)) $this->setDateUploaded($data->date_uploaded);
        }
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getParkId() {
        return $this->par_id;
    }

    public function setParkId($par_id) {
        $this->par_id = $par_id;
    }

    public function getNumber() {
        return $this->number;
    }

    public function setNumber($number) {
        $this->number = $number;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getFunction() {
        return $this->function;
    }

    public function setFunction($function) {
        $this->function = $function;
    }

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function getMethod() {
        return $this->method;
    }

    public function setMethod($method) {
        $this->method = $method;
    }

    public function getModel() {
        return $this->model;
    }

    public function setModel($model) {
        $this->model = $model;
    }

    public function getBrand() {
        return $this->brand;
    }

    public function setBrand($brand) {
        $this->brand = $brand;
    }

    public function getMultiplier() {
        return $this->multiplier;
    }

    public function setMultiplier($multiplier) {
        $this->multiplier = $multiplier;
    }

    public function getLat() {
        return $this->lat;
    }

    public function setLat($lat) {
        $this->lat = $lat;
    }

    public function getLon() {
        return $this->lon;
    }

    public function setLon($lon) {
        $this->lon = $lon;
    }

    public function getSeeInsightId() {
        return $this->seeinsight_id;
    }

    public function setSeeInsightId($seeinsight_id) {
        $this->seeinsight_id = $seeinsight_id;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getDateUploaded() {
        return $this->date_uploaded;
    }

    public function setDateUploaded($date_uploaded) {
        $this->date_uploaded = $date_uploaded;
    }

}
