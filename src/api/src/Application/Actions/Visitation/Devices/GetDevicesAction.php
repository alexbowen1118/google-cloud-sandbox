<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions\Visitation\Devices;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;

class GetDevicesAction extends Action
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response
  {
    $deviceDAO = $this->DAOFactory->createDeviceDAO();
    $result = [
      'devices' => $deviceDAO->getAllDevices()
    ];

    $this->logger->info("Total device list was viewed.");

    return $this->respondWithData($result);
  }
}
