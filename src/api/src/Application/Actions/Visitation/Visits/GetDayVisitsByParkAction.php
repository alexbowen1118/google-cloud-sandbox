<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions\Visitation\Visits;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;

class GetDayVisitsByParkAction extends Action
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response
  {
    $park = (int) $this->resolveArg('par_id');
    
    $visitDAO = $this->DAOFactory->createVisitDAO();
    $result = [
      'visits' => $visitDAO->getDayVisitsByPark($park)
    ];

    $this->logger->info("Park visit list aggregated by day was viewed.");

    return $this->respondWithData($result);
  }
}
