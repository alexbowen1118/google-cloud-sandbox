<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions\Visitation\Visits;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;

class GetVisitsByParkAction extends Action
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response
  {
    $park = (int) $this->resolveArg('par_id');
    
    $visitDAO = $this->DAOFactory->createVisitDAO();
    $result = [
      'visits' => $visitDAO->getVisitsByPark($park)
    ];

    $this->logger->info("Park visit list was viewed.");

    return $this->respondWithData($result);
  }
}
