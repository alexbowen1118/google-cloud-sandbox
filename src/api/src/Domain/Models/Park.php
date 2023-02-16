<?php
declare(strict_types=1);
namespace DPR\API\Domain\Models;

use DPR\API\Domain\DomainModel;

class Park extends DomainModel {

    public $id;
    public $code;
    public $reg_id;
    public $admin_by;
    public $name;
    public $lat;
    public $lon;

    function __construct($data) {
        $this->JSONFields = array('id', 'code', 'reg_id', 'admin_by', 'name', 'lat', 'lon');
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
            if(isset($data->code)) $this->setParkCode($data->code);
            if(isset($data->reg_id)) $this->setRegionId($data->reg_id);
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

    public function setParkCode($par_code) {
        $this->code = $par_code;
    }

    public function getRegionId() {
        return $this->reg_id;
    }

    public function setRegionId($reg_id) {
        $this->reg_id = $reg_id;
    }

    public function getAdminBy() {
        return $this->admin_by;
    }

    public function setAdminBy($admin_by) {
        $this->admin_by = $admin_by;
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
