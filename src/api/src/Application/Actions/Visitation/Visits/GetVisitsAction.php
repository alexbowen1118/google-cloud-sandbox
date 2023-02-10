<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions\Visitation\Visits;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;

class GetVisitsAction extends Action
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response
  {
    $visitDAO = $this->DAOFactory->createVisitDAO();
    $result = [
      'visits' => $visitDAO->getAllVisits()
    ];

    $this->logger->info("Total visit list was viewed.");

    return $this->respondWithData($result);
  }
}
