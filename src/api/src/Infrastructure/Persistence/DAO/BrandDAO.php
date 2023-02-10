<?php
declare(strict_types=1);
namespace DPR\API\Infrastructure\Persistence\DAO;

use DPR\API\Domain\Models;

class BrandDAO extends DAO {

  protected $BaseQuery = "SELECT * FROM ncparks.brand ";



  function createBrand(Models\Brand $Brand) {
    $DBConn = $this->DBPool->request();
    $DBConn->query("INSERT INTO ncparks.brand (brn_id, brn_name, brn_status) VALUES (?, ?, ?)",
      array(
        array("value" => $Brand->id, "type" => \PDO::PARAM_INT),
        array("value" => $Brand->name, "type" => \PDO::PARAM_STR),
        array("value" => $Brand->status, "type" => \PDO::PARAM_INT)
      ));
    $id = $DBConn->insertID();
    $this->DBPool->release($DBConn);
    return $this->getBrandById($id);
  }



  function getBrandById($id) {
    $DBConn = $this->DBPool->request();
    $DBConn->query($this->BaseQuery." WHERE brn_status = 1 AND brn_id = ?",
      array(
        array("value" => $id, "type" => \PDO::PARAM_INT)
      ));
    $Brand = null;
    if($DBConn->nextRow()) {
      $Brand = new Models\Brand($DBConn->getRow());
    }
    $this->DBPool->release($DBConn);
    return $Brand;
  }



  function getAllBrands() {
    $DBConn = $this->DBPool->request();
    $DBConn->query($this->BaseQuery." WHERE brn_status = 1");
    $Brands = [];
    while($DBConn->nextRow()) {
      $Brands[] = new Models\Brand($DBConn->getRow());
    }
    $this->DBPool->release($DBConn);
    return $Brands;
  }



  function updateBrand(Models\Brand $Brand) {
    $DBConn = $this->DBPool->request();
    $DBConn->query("UPDATE ncparks.brand SET brn_name = ?, brn_status = ? WHERE brn_id = ?",
      array(
        array("value" => $Brand->name, "type" => \PDO::PARAM_STR),
        array("value" => $Brand->status, "type" => \PDO::PARAM_INT),
        array("value" => $Brand->id, "type" => \PDO::PARAM_INT)
      ));
    $this->DBPool->release($DBConn);
    return $this->getBrandById($Brand->id);
  }



  function deleteBrand($id) {
    $Brand = $this->getBrandById($id);
    $Brand->setStatus(0);
    $DBConn = $this->DBPool->request();
    $DBConn->query("UPDATE ncparks.brand SET brn_name = ?, brn_status = ? WHERE brn_id = ?",
      array(
        array("value" => $Brand->name, "type" => \PDO::PARAM_STR),
        array("value" => $Brand->status, "type" => \PDO::PARAM_INT),
        array("value" => $Brand->id, "type" => \PDO::PARAM_INT)
      ));
    $this->DBPool->release($DBConn);
    return $this->getBrandById($Brand->id);
  }
  
}
