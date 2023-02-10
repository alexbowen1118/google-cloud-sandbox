<?php
declare(strict_types=1);

namespace DPR\API\Infrastructure\Persistence\DAO;

use DPR\API\Infrastructure\Persistence\DBPool;

abstract class DAO {
  /**
   * @var DBPool
   */
  protected $DBPool;

  public function __construct(DBPool $DBPool) {
    $this->DBPool = $DBPool;
  }
}