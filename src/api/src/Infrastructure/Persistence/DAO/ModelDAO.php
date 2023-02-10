<?php
declare(strict_types=1);
namespace DPR\API\Infrastructure\Persistence\DAO;

use DPR\API\Domain\Models;

class ModelDAO extends DAO {

  protected $BaseQuery = "SELECT * FROM ncparks.model ";



  function createModel(Models\Model $Model) {
    $DBConn = $this->DBPool->request();
    $DBConn->query("INSERT INTO ncparks.model (mdl_id, mdl_name, mdl_status) VALUES (?, ?, ?)",
      array(
        array("value" => $Model->id, "type" => \PDO::PARAM_INT),
        array("value" => $Model->name, "type" => \PDO::PARAM_STR),
        array("value" => $Model->status, "type" => \PDO::PARAM_INT)
      ));
    $id = $DBConn->insertID();
    $this->DBPool->release($DBConn);
    return $this->getModelById($id);
  }



  function getModelById($id) {
    $DBConn = $this->DBPool->request();
    $DBConn->query($this->BaseQuery." WHERE mdl_status = 1 AND mdl_id = ?",
      array(
        array("value" => $id, "type" => \PDO::PARAM_INT)
      ));
    $Model = null;
    if($DBConn->nextRow()) {
      $Model = new Models\Model($DBConn->getRow());
    }
    $this->DBPool->release($DBConn);
    return $Model;
  }



  function getAllModels() {
    $DBConn = $this->DBPool->request();
    $DBConn->query($this->BaseQuery." WHERE mdl_status = 1");
    $Models = [];
    while($DBConn->nextRow()) {
      $Models[] = new Models\Model($DBConn->getRow());
    }
    $this->DBPool->release($DBConn);
    return $Models;
  }



  function updateModel(Models\Model $Model) {
    $DBConn = $this->DBPool->request();
    $DBConn->query("UPDATE ncparks.model SET mdl_name = ?, mdl_status = ? WHERE mdl_id = ?",
      array(
        array("value" => $Model->name, "type" => \PDO::PARAM_STR),
        array("value" => $Model->status, "type" => \PDO::PARAM_INT),
        array("value" => $Model->id, "type" => \PDO::PARAM_INT)
      ));
    $this->DBPool->release($DBConn);
    return $this->getModelById($Model->id);
  }



  function deleteModel($id) {
    $Model = $this->getModelById($id);
    $Model->setStatus(0);
    $DBConn = $this->DBPool->request();
    $DBConn->query("UPDATE ncparks.model SET mdl_name = ?, mdl_status = ? WHERE mdl_id = ?",
      array(
        array("value" => $Model->name, "type" => \PDO::PARAM_STR),
        array("value" => $Model->status, "type" => \PDO::PARAM_INT),
        array("value" => $Model->id, "type" => \PDO::PARAM_INT)
      ));
    $this->DBPool->release($DBConn);
    return $this->getModelById($Model->id);
  }
  
}
