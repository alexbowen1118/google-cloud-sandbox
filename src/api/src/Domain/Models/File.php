<?php
declare(strict_types=1);
namespace DPR\API\Domain\Models;

use DPR\API\Domain\DomainModel;

class File extends DomainModel {
  public $id = null;
  public $name = null;

  function __construct($data) {
    $this->JSONFields = array("id", "name");
    if(is_array($data)) { //Coming from the database
      $this->id = (int)$data['mid'];
      $this->name = $data['filename'];
    }
    elseif(is_object($data)) { //Coming from JSON
      if(isset($data->id)) $this->id = $data->id;
      if(isset($data->name)) $this->name = $data->name;
    }
  }


}
