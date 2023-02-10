<?php
declare(strict_types=1);
namespace DPR\API\Infrastructure\Persistence\DAO;

use DPR\API\Domain\Models;

class DeviceDAO extends DAO {

    protected $BaseQuery = "SELECT * FROM ncparks.device JOIN ncparks.function ON dev_function=fnc_id JOIN ncparks.type ON dev_type=typ_id 
    JOIN ncparks.method ON dev_method=mtd_id JOIN ncparks.model ON dev_model=mdl_id JOIN ncparks.brand ON dev_brand=brn_id ";

    function createDevice(Models\Device $Device) {
        $DBConn = $this->DBPool->request();
        $DBConn->query("INSERT INTO ncparks.device (dev_id, dev_par_id, dev_number, dev_name, dev_function, dev_type, dev_method, dev_model, dev_brand,
         dev_multiplier, dev_lat, dev_lon, dev_seeinsight_id, dev_status, dev_date_uploaded) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            array(
                array("value" => $Device->id, "type" => \PDO::PARAM_INT),
                array("value" => $Device->par_id, "type" => \PDO::PARAM_INT),
                array("value" => $Device->number, "type" => \PDO::PARAM_STR),
                array("value" => $Device->name, "type" => \PDO::PARAM_STR),
                array("value" => $Device->function, "type" => \PDO::PARAM_INT),
                array("value" => $Device->type, "type" => \PDO::PARAM_INT),
                array("value" => $Device->method, "type" => \PDO::PARAM_INT),
                array("value" => $Device->model, "type" => \PDO::PARAM_INT),
                array("value" => $Device->brand, "type" => \PDO::PARAM_INT),
                array("value" => $Device->multiplier, "type" => \PDO::PARAM_STR),
                array("value" => $Device->lat, "type" => \PDO::PARAM_STR),
                array("value" => $Device->lon, "type" => \PDO::PARAM_STR),
                array("value" => $Device->seeinsight_id, "type" => \PDO::PARAM_STR),
                array("value" => $Device->status, "type" => \PDO::PARAM_INT),
                array("value" => $Device->date_uploaded, "type" => \PDO::PARAM_STR)
            ));
        $id = $DBConn->insertID();
        $this->DBPool->release($DBConn);
        return $this->getDeviceById($id);
    }



    function getDeviceById($id) {
        $DBConn = $this->DBPool->request();
        $DBConn->query($this->BaseQuery." WHERE dev_status = 1 AND dev_id = ?",
            array(
                array("value" => $id, "type" => \PDO::PARAM_INT)
            ));
        $Device = null;
        if($DBConn->nextRow()) {
            $Device = new Models\Device($DBConn->getRow());
        }
        $this->DBPool->release($DBConn);
        return $Device;
    }



    function getAllDevices() {
        $DBConn = $this->DBPool->request();
        $DBConn->query($this->BaseQuery." WHERE dev_status = 1");
        $Devices = [];
        while($DBConn->nextRow()) {
            $Devices[] = new Models\Device($DBConn->getRow());
        }
        $this->DBPool->release($DBConn);
        return $Devices;
    }



    function getDevicesByPark($dev_id) {
        $DBConn = $this->DBPool->request();
        $DBConn->query($this->BaseQuery." WHERE dev_status = 1 AND dev_par_id = ?",
            array(
                array("value" => $dev_id, "type" => \PDO::PARAM_INT)
            ));
        $Devices = [];
        while($DBConn->nextRow()) {
            $Devices[] = new Models\Device($DBConn->getRow());
        }
        $this->DBPool->release($DBConn);
        return $Devices;
    }



    function updateDevice(Models\Device $Device) {
        $DBConn = $this->DBPool->request();
        $DBConn->query("UPDATE ncparks.device SET dev_par_id = ?, dev_number = ?, dev_name = ?, dev_function = ?, dev_type = ?, dev_method = ?, dev_model = ?,
         dev_brand = ?, dev_multiplier = ?, dev_lat = ?, dev_lon = ?, dev_seeinsight_id = ?, dev_status = ?, dev_date_uploaded = ? WHERE dev_id = ?",
            array(
                array("value" => $Device->par_id, "type" => \PDO::PARAM_INT),
                array("value" => $Device->number, "type" => \PDO::PARAM_STR),
                array("value" => $Device->name, "type" => \PDO::PARAM_STR),
                array("value" => $Device->function, "type" => \PDO::PARAM_INT),
                array("value" => $Device->type, "type" => \PDO::PARAM_INT),
                array("value" => $Device->method, "type" => \PDO::PARAM_INT),
                array("value" => $Device->model, "type" => \PDO::PARAM_INT),
                array("value" => $Device->brand, "type" => \PDO::PARAM_INT),
                array("value" => $Device->multiplier, "type" => \PDO::PARAM_STR),
                array("value" => $Device->lat, "type" => \PDO::PARAM_STR),
                array("value" => $Device->lon, "type" => \PDO::PARAM_STR),
                array("value" => $Device->seeinsight_id, "type" => \PDO::PARAM_STR),
                array("value" => $Device->status, "type" => \PDO::PARAM_INT),
                array("value" => $Device->date_uploaded, "type" => \PDO::PARAM_STR),
                array("value" => $Device->id, "type" => \PDO::PARAM_INT)
            ));
        $this->DBPool->release($DBConn);
        return $this->getDeviceById($Device->id);
    }



    function deleteDevice($id) {
        $Device = $this->getDeviceById($id);
        $Device->setStatus(0);
        $DBConn = $this->DBPool->request();
        $DBConn->query("UPDATE ncparks.device SET dev_par_id = ?, dev_number = ?, dev_name = ?, dev_function = ?, dev_type = ?, dev_method = ?, dev_model = ?,
         dev_brand = ?, dev_multiplier = ?, dev_lat = ?, dev_lon = ?, dev_seeinsight_id = ?, dev_status = ?, dev_date_uploaded = ? WHERE dev_id = ?",
            array(
                array("value" => $Device->par_id, "type" => \PDO::PARAM_INT),
                array("value" => $Device->number, "type" => \PDO::PARAM_STR),
                array("value" => $Device->name, "type" => \PDO::PARAM_STR),
                array("value" => $Device->function, "type" => \PDO::PARAM_INT),
                array("value" => $Device->type, "type" => \PDO::PARAM_INT),
                array("value" => $Device->method, "type" => \PDO::PARAM_INT),
                array("value" => $Device->model, "type" => \PDO::PARAM_INT),
                array("value" => $Device->brand, "type" => \PDO::PARAM_INT),
                array("value" => $Device->multiplier, "type" => \PDO::PARAM_STR),
                array("value" => $Device->lat, "type" => \PDO::PARAM_STR),
                array("value" => $Device->lon, "type" => \PDO::PARAM_STR),
                array("value" => $Device->seeinsight_id, "type" => \PDO::PARAM_STR),
                array("value" => $Device->status, "type" => \PDO::PARAM_INT),
                array("value" => $Device->date_uploaded, "type" => \PDO::PARAM_STR),
                array("value" => $Device->id, "type" => \PDO::PARAM_INT)
            ));
        $this->DBPool->release($DBConn);
        return $this->getDeviceById($Device->id);
    }



    function getDeviceIdBySeeInsightsId($seeinsight_id) {
        $DBConn = $this->DBPool->request();
        $DBConn->query($this->BaseQuery." WHERE dev_seeinsight_id = ?",
            array(
                array("value" => $seeinsight_id, "type" => \PDO::PARAM_STR)
            ));
        $par_id = null;
        if($DBConn->nextRow()) {
            $par_id = $DBConn->getRow()['dev_id'];
        }
        $this->DBPool->release($DBConn);
        return $par_id;
    }

    function getDeviceIdByDeviceNumber($number) {
        $DBConn = $this->DBPool->request();
        $DBConn->query($this->BaseQuery." WHERE dev_number = ?",
            array(
                array("value" => $number, "type" => \PDO::PARAM_STR)
            ));
        $id = null;
        if($DBConn->nextRow()) {
            $id = $DBConn->getRow()['dev_id'];
        }
        $this->DBPool->release($DBConn);
        return $id;
    }

}