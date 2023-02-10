<?php
declare(strict_types=1);
namespace DPR\API\Infrastructure\Persistence\DAO;

use DPR\API\Domain\Models;

class ParkDAO extends DAO {

  protected $BaseQuery = "SELECT * FROM ncparks.park ";


  
  function getParkById($id) {
    $DBConn = $this->DBPool->request();
    $DBConn->query($this->BaseQuery." WHERE par_id = ?",
      array(
        array("value" => $id, "type" => \PDO::PARAM_INT)
      ));
    $Park = null;
    if($DBConn->nextRow()) {
      $Park = new Models\Park($DBConn->getRow());
    }
    $this->DBPool->release($DBConn);
    return $Park;
  }



  function getParkIdByCode($code) {
    $DBConn = $this->DBPool->request();
    $DBConn->query($this->BaseQuery." WHERE par_code = ?",
      array(
        array("value" => $code, "type" => \PDO::PARAM_STR)
      ));
    $par_id = null;
    if($DBConn->nextRow()) {
      $par_id = $DBConn->getRow()['par_id'];
    }
    $this->DBPool->release($DBConn);
    return $par_id;
  }


  
  function getAllParks() {
    $DBConn = $this->DBPool->request();
    $DBConn->query($this->BaseQuery);
    $Parks = [];
    while ($DBConn->nextRow()) {
      $Parks[] = new Models\Park($DBConn->getRow());
    }
    $this->DBPool->release($DBConn);
    return $Parks;
  }

}
