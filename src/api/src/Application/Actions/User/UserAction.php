<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions\User;

use DPR\API\Application\Actions\Action;
use DPR\API\Domain\User\UserRepository;
use Psr\Log\LoggerInterface;

abstract class UserAction extends Action
{
    protected UserRepository $userRepository;

    public function __construct(LoggerInterface $logger, UserRepository $userRepository)
    {
        parent::__construct($logger);
        $this->userRepository = $userRepository;
    }
}
