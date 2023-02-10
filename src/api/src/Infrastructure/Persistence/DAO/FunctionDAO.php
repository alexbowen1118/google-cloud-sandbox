<?php
declare(strict_types=1);
namespace DPR\API\Infrastructure\Persistence\DAO;

use DPR\API\Domain\Models;

class FunctionDAO extends DAO {

  protected $BaseQuery = "SELECT * FROM ncparks.function ";



  function createFunction(Models\DeviceFunction $Function) {
    $DBConn = $this->DBPool->request();
    $DBConn->query("INSERT INTO ncparks.function (fnc_id, fnc_name, fnc_status) VALUES (?, ?, ?)",
      array(
        array("value" => $Function->id, "type" => \PDO::PARAM_INT),
        array("value" => $Function->name, "type" => \PDO::PARAM_STR),
        array("value" => $Function->status, "type" => \PDO::PARAM_INT)
      ));
    $id = $DBConn->insertID();
    $this->DBPool->release($DBConn);
    return $this->getFunctionById($id);
  }



  function getFunctionById($id) {
    $DBConn = $this->DBPool->request();
    $DBConn->query($this->BaseQuery." WHERE fnc_status = 1 AND fnc_id = ?",
      array(
        array("value" => $id, "type" => \PDO::PARAM_INT)
      ));
    $Function = null;
    if($DBConn->nextRow()) {
      $Function = new Models\DeviceFunction($DBConn->getRow());
    } 
    $this->DBPool->release($DBConn);
    return $Function;
  }



  function getAllFunctions() {
    $DBConn = $this->DBPool->request();
    $DBConn->query($this->BaseQuery." WHERE fnc_status = 1");
    $Functions = [];
    while($DBConn->nextRow()) {
      $Functions[] = new Models\DeviceFunction($DBConn->getRow());
    }
    $this->DBPool->release($DBConn);
    return $Functions;
  }



  function updateFunction(Models\DeviceFunction $Function) {
    $DBConn = $this->DBPool->request();
    $DBConn->query("UPDATE ncparks.function SET fnc_name = ?, fnc_status = ? WHERE fnc_id = ?",
      array(
        array("value" => $Function->name, "type" => \PDO::PARAM_STR),
        array("value" => $Function->status, "type" => \PDO::PARAM_INT),
        array("value" => $Function->id, "type" => \PDO::PARAM_INT)
      ));
    $this->DBPool->release($DBConn);
    return $this->getFunctionById($Function->id);
  }



  function delete($id) {
    $Function = $this->getFunctionById($id);
    $Function->setStatus(0);
    $DBConn->query("UPDATE ncparks.function SET fnc_name = ?, fnc_status = ? WHERE fnc_id = ?",
      array(
        array("value" => $Function->name, "type" => \PDO::PARAM_STR),
        array("value" => $Function->status, "type" => \PDO::PARAM_INT),
        array("value" => $Function->id, "type" => \PDO::PARAM_INT)
      ));
    $this->DBPool->release($DBConn);
    return $this->getFunctionById($Function->id);
  }
  
}
