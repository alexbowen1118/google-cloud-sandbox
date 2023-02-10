<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions\Parks;

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
      'parks' => $parkDAO->getAllParks()
    ];

    $this->logger->info("Park list was viewed.");

    return $this->respondWithData($result);
  }
}
