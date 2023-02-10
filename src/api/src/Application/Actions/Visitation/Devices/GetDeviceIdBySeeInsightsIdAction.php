<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions\Parks;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;

class GetDeviceIdBySeeInsightsIdAction extends Action
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response
  {
    $seeinsights_id = $this->resolveArg('dev_seeinsights_id');
    
    $deviceDAO = $this->DAOFactory->createParkDAO();
    $result = [
      'dev_id' => $deviceDAO->getDeviceIdBySeeInsightsId($seeinsights_id)
    ];

    $this->logger->info("Device ID was retrieved.");

    return $this->respondWithData($result);
  }
}
