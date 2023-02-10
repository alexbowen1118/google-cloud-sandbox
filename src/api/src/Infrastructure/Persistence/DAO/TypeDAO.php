<?php
declare(strict_types=1);
namespace DPR\API\Infrastructure\Persistence\DAO;

use DPR\API\Domain\Models;

class TypeDAO extends DAO {

  protected $BaseQuery = "SELECT * FROM ncparks.type ";



  function createType(Models\Type $Type) {
    $DBConn = $this->DBPool->request();
    $DBConn->query("INSERT INTO ncparks.type (typ_id, typ_name, typ_status) VALUES (?, ?, ?)",
      array(
        array("value" => $Type->id, "type" => \PDO::PARAM_INT),
        array("value" => $Type->name, "type" => \PDO::PARAM_STR),
        array("value" => $Type->status, "type" => \PDO::PARAM_INT)
      ));
    $id = $DBConn->insertID();
    $this->DBPool->release($DBConn);
    return $this->getTypeById($id);
  }



  function getTypeById($id) {
    $DBConn = $this->DBPool->request();
    $DBConn->query($this->BaseQuery." WHERE typ_status = 1 AND typ_id = ?",
      array(
        array("value" => $id, "type" => \PDO::PARAM_INT)
      ));
    $Type = null;
    if($DBConn->nextRow()) {
      $Type = new Models\Type($DBConn->getRow());
    }
    $this->DBPool->release($DBConn);
    return $Type;
  }



  function getAllTypes() {
    $DBConn = $this->DBPool->request();
    $DBConn->query($this->BaseQuery." WHERE typ_status = 1");
    $Types = [];
    while($DBConn->nextRow()) {
      $Types[] = new Models\Type($DBConn->getRow());
    }
    $this->DBPool->release($DBConn);
    return $Types;
  }



  function updateType(Models\Type $Type) {
    $DBConn = $this->DBPool->request();
    $DBConn->query("UPDATE ncparks.type SET typ_name = ?, typ_status = ? WHERE typ_id = ?",
      array(
        array("value" => $Type->name, "type" => \PDO::PARAM_STR),
        array("value" => $Type->status, "type" => \PDO::PARAM_INT),
        array("value" => $Type->id, "type" => \PDO::PARAM_INT)
      ));
    $this->DBPool->release($DBConn);
    return $this->getTypeById($Type->id);
  }



  function deleteType($id) {
    $Type = $this->getTypeById($id);
    $Type->setStatus(0);
    $DBConn = $this->DBPool->request();
    $DBConn->query("UPDATE ncparks.type SET typ_name = ?, typ_status = ? WHERE typ_id = ?",
      array(
        array("value" => $Type->name, "type" => \PDO::PARAM_STR),
        array("value" => $Type->status, "type" => \PDO::PARAM_INT),
        array("value" => $Type->id, "type" => \PDO::PARAM_INT)
      ));
    $this->DBPool->release($DBConn);
    return $this->getTypeById($Type->id);
  }
  
}
