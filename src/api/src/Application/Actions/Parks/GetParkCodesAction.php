<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions\Parks;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;

class GetParkCodesAction extends Action
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {

        $parkDAO = $this->DAOFactory->createParkDAO();
        $result[] = $parkDAO->getParkCodes();

        $this->logger->info("Park codes were retrieved.");
        return $this->respondWithData($result);
    }
}
