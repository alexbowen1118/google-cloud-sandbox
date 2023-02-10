<?php

declare(strict_types=1);

namespace DPR\API\Infrastructure\Persistence\DAO\Common;

use DPR\API\Domain\Models\User;
use DPR\API\Infrastructure\Persistence\DAO\DAO;

class UserDAO extends DAO
{
    protected $BaseQuery = "SELECT * FROM ncparks.user ";
}
