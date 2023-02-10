<?php
declare(strict_types=1);
namespace DPR\API\Infrastructure\Persistence\DAO;

use DPR\API\Domain\Models;

class CounterRuleDAO extends DAO {

  protected $BaseQuery = "SELECT * FROM ncparks.counter_rule JOIN ncparks.device ON rul_dev_id=dev_id ";



  function createCounterRule(Models\CounterRule $CounterRule) {
    $DBConn = $this->DBPool->request();
    $DBConn->query("INSERT INTO ncparks.counter_rule (rul_id, rul_dev_id, rul_start, rul_end, rul_multiplier, rul_status) VALUES (?, ?, ?, ?, ?, ?)",
      array(
        array("value" => $CounterRule->id, "type" => \PDO::PARAM_INT),
        array("value" => $CounterRule->dev_id, "type" => \PDO::PARAM_INT),
        array("value" => $CounterRule->start, "type" => \PDO::PARAM_STR),
        array("value" => $CounterRule->end, "type" => \PDO::PARAM_STR),
        array("value" => $CounterRule->multiplier, "type" => \PDO::PARAM_STR),
        array("value" => $CounterRule->status, "type" => \PDO::PARAM_INT)
      ));
    $id = $DBConn->insertID();
    $this->DBPool->release($DBConn);
    return $this->getCounterRuleById($id);
  }



  function getCounterRuleById($id) {
    $DBConn = $this->DBPool->request();
    $DBConn->query($this->BaseQuery." WHERE rul_status = 1 AND rul_id = ?",
      array(
        array("value" => $id, "type" => \PDO::PARAM_INT)
      ));
    $CounterRule = null;
    if($DBConn->nextRow()) {
      $CounterRule = new Models\CounterRule($DBConn->getRow());
    }
    $this->DBPool->release($DBConn);
    return $CounterRule;
  }



  function getAllCounterRules() {
    $DBConn = $this->DBPool->request();
    $DBConn->query($this->BaseQuery." WHERE rul_status = 1");
    $CounterRules = [];
    while($DBConn->nextRow()) {
      $CounterRules[] = new Models\CounterRule($DBConn->getRow());
    }
    $this->DBPool->release($DBConn);
    return $CounterRules;
  }



  function getCounterRulesByDevice($dev_id) {
    $DBConn = $this->DBPool->request();
    $DBConn->query($this->BaseQuery." WHERE rul_status = 1 AND rul_dev_id = ?",
      array(
        array("value" => $dev_id, "type" => \PDO::PARAM_INT)
      ));
    $CounterRules = [];
    while($DBConn->nextRow()) {
      $CounterRules[] = new Models\CounterRule($DBConn->getRow());
    }
    $this->DBPool->release($DBConn);
    return $CounterRules;
  }



  function updateCounterRule(Models\CounterRule $CounterRule) {
    $DBConn = $this->DBPool->request();
    $DBConn->query("UPDATE ncparks.counter_rule SET rul_dev_id = ?, rul_start = ?, rul_end = ?, rul_multiplier = ?, rul_status = ? WHERE rul_id = ?",
      array(
        array("value" => $CounterRule->dev_id, "type" => \PDO::PARAM_INT),
        array("value" => $CounterRule->start, "type" => \PDO::PARAM_STR),
        array("value" => $CounterRule->end, "type" => \PDO::PARAM_STR),
        array("value" => $CounterRule->multiplier, "type" => \PDO::PARAM_STR),
        array("value" => $CounterRule->status, "type" => \PDO::PARAM_INT),
        array("value" => $CounterRule->id, "type" => \PDO::PARAM_INT)
      ));
    $this->DBPool->release($DBConn);
    return $this->getCounterRuleById($CounterRule->id);
  }



  function deleteCounterRule($id) {
    $DBConn = $this->DBPool->request();
    $DBConn->query("DELETE FROM ncparks.counter_rule WHERE rul_id = ?",
      array(
        array("value" => $id, "type" => \PDO::PARAM_INT)
      ));
    $this->DBPool->release($DBConn);
    return $this->getCounterRuleById($id);
  }
  
}
