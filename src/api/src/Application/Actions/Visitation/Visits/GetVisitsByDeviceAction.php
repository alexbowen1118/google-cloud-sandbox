<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions\Visitation\Visits;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;

class GetVisitsByDeviceAction extends Action
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response
  {
    $device = (int) $this->resolveArg('dev_id');

    $visitDAO = $this->DAOFactory->createVisitDAO();
    $result = [
      'visits' => $visitDAO->getVisitsByDevice($device)
    ];

    $this->logger->info("Device visit list was viewed.");

    return $this->respondWithData($result);
  }
}
