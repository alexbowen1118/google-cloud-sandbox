<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions\Visitation\Devices;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;

class GetDevicesByParkAction extends Action
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response
  {
    $park = $this->resolveArg('par_id');
    
    $deviceDAO = $this->DAOFactory->createDeviceDAO();
    $result = [
      'devices' => $deviceDAO->getDevicesByPark($park)
    ];

    $this->logger->info("Park device list was viewed.");

    return $this->respondWithData($result);
  }
}
