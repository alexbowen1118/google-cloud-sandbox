<?php
declare(strict_types=1);

namespace DPR\API\Domain\User;

use DPR\API\Domain\DomainException\DomainRecordNotFoundException;

class UserNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The user you requested does not exist.';
}
