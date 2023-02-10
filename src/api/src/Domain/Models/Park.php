<?php
declare(strict_types=1);
namespace DPR\API\Domain\Models;

use DPR\API\Domain\DomainModel;

class Park extends DomainModel {

    public $id;
    public $parkCode;
    public $regionId;
    public $adminBy;
    public $name;
    public $lat;
    public $lon;

    function __construct($data) {
        $this->JSONFields = array('id', 'park_code', 'region_id', 'admin_by', 'name', 'lat', 'lon');
        if(is_array($data)) { //Coming from the database
            $this->setId($data['par_id']);
            $this->setParkCode($data['par_code']);
            $this->setRegionId($data['par_reg_id']);
            $this->setAdminBy($data['par_admin_by']);
            $this->setName($data['par_name']);
            $this->setLat($data['par_lat']);
            $this->setLon($data['par_lon']);
        }
        elseif(is_string($data)) { //Coming from JSON
            $data = json_decode($data);
            if(isset($data->id)) $this->setId($data->id);
            if(isset($data->park_code)) $this->setParkCode($data->park_code);
            if(isset($data->region_id)) $this->setRegionId($data->region_id);
            if(isset($data->admin_by)) $this->setAdminBy($data->admin_by);
            if(isset($data->name)) $this->setName($data->name);
            if(isset($data->lat)) $this->setLat($data->lat);
            if(isset($data->lon)) $this->setLon($data->lon);
        }
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getParkCode() {
        return $this->parkCode;
    }

    public function setParkCode($parkCode) {
        $this->parkCode = $parkCode;
    }

    public function getRegionId() {
        return $this->regionId;
    }

    public function setRegionId($regionId) {
        $this->regionId = $regionId;
    }

    public function getAdminBy() {
        return $this->adminBy;
    }

    public function setAdminBy($adminBy) {
        $this->adminBy = $adminBy;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
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

}
