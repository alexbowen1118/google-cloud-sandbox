<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions\Visitation\Visits;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;

class GetVisitAction extends Action
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response
  {
    $id = (int) $this->resolveArg('vis_id');
    
    $visitDAO = $this->DAOFactory->createVisitDAO();
    $result = [
      'visit' => $visitDAO->getVisitById($id)
    ];

    $this->logger->info("Device visit was viewed.");

    return $this->respondWithData($result);
  }
}
