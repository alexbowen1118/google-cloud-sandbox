<?php
declare(strict_types=1);
namespace DPR\API\Infrastructure\Persistence\DAO;

use DPR\API\Domain\Models;

class MethodDAO extends DAO {

  protected $BaseQuery = "SELECT * FROM ncparks.method ";



  function createMethod(Models\Method $Method) {
    $DBConn = $this->DBPool->request();
    $DBConn->query("INSERT INTO ncparks.method (mtd_id, mtd_name, mtd_status) VALUES (?, ?, ?)",
      array(
        array("value" => $Method->id, "type" => \PDO::PARAM_INT),
        array("value" => $Method->name, "type" => \PDO::PARAM_STR),
        array("value" => $Method->status, "type" => \PDO::PARAM_INT)
      ));
    $id = $DBConn->insertID();
    $this->DBPool->release($DBConn);
    return $this->getMethodById($id);
  }



  function getMethodById($id) {
    $DBConn = $this->DBPool->request();
    $DBConn->query($this->BaseQuery." WHERE mtd_status = 1 AND mtd_id = ?",
      array(
        array("value" => $id, "type" => \PDO::PARAM_INT)
      ));
    $Method = null;
    if($DBConn->nextRow()) {
      $Method = new Models\Method($DBConn->getRow());
    }
    $this->DBPool->release($DBConn);
    return $Method;
  }



  function getAllMethods() {
    $DBConn = $this->DBPool->request();
    $DBConn->query($this->BaseQuery." WHERE mtd_status = 1");
    $Methods = [];
    while($DBConn->nextRow()) {
      $Methods[] = new Models\Method($DBConn->getRow());
    }
    $this->DBPool->release($DBConn);
    return $Methods;
  }



  function updateMethod(Models\Method $Method) {
    $DBConn = $this->DBPool->request();
    $DBConn->query("UPDATE ncparks.method SET mtd_name = ?, mtd_status = ? WHERE mtd_id = ?",
      array(
        array("value" => $Method->name, "type" => \PDO::PARAM_STR),
        array("value" => $Method->status, "type" => \PDO::PARAM_INT),
        array("value" => $Method->id, "type" => \PDO::PARAM_INT)
      ));
    $this->DBPool->release($DBConn);
    return $this->getMethodById($Method->id);
  }



  function deleteMethod($id) {
    $Method = $this->getMethodById($id);
    $Method->setStatus(0);
    $DBConn = $this->DBPool->request();
    $DBConn->query("UPDATE ncparks.method SET mtd_name = ?, mtd_status = ? WHERE mtd_id = ?",
      array(
        array("value" => $Method->name, "type" => \PDO::PARAM_STR),
        array("value" => $Method->status, "type" => \PDO::PARAM_INT),
        array("value" => $Method->id, "type" => \PDO::PARAM_INT)
      ));
    $this->DBPool->release($DBConn);
    return $this->getMethodById($Method->id);
  }
  
}
