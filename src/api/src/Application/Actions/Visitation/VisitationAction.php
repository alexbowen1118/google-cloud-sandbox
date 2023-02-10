<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions\Visitation;

use DPR\API\Application\Actions\Action;
use DPR\API\Domain\Ubidots\UbidotsAPI;
use DPR\API\Domain\User\UserRepository;
use Psr\Log\LoggerInterface;
use DPR\API\Infrastructure\Persistence\DAO\DAOFactory;

abstract class VisitationAction extends Action
{
    protected UbidotsAPI $ubidotsAPI;

    public function __construct(LoggerInterface $logger, DAOFactory $DAOFactory, UbidotsAPI $ubidotsAPI)
    {
        parent::__construct($logger, $DAOFactory);
        $this->ubidotsAPI = $ubidotsAPI;
    }
}
