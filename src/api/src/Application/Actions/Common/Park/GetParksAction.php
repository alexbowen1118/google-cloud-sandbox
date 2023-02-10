<?php

declare(strict_types=1);

namespace DPR\API\Application\Actions\Common\Park;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;

class GetParksAction extends Action
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {

        $parkDAO = $this->DAOFactory->createParkDAO();

        $result = [
            'parks' => $parkDAO->getParks()
        ];

        $this->logger->info("Park code list was retrieved.");

        return $this->respondWithData($result);
    }
}
