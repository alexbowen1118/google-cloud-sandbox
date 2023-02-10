<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions\Visitation\Devices;

use Psr\Http\Message\ResponseInterface as Response;
use DPR\API\Application\Actions\Visitation\VisitationAction;

class FetchLegacyDevicesAction extends VisitationAction
{
  /**
   * {@inheritdoc}
   */

  //make sure to change to protected after testing
  public function action(): Response
  {
    $data = null;
    $pdao = $this->DAOFactory->createParkDAO();
    $ddao = $this->DAOFactory->createDeviceDAO();
    $result = [];

    $data = $this->ubidotsAPI->fetchLegacyUbidotsData("d");
    foreach($data as $device){
        $device->setParkId($pdao->getParkIdByCode($device->getParkId()));
        $result[] = $ddao->createDevice($device);
    }

    $this->logger->info("Data fetched successfully.");

    return $this->respondWithData($result);
  }
}
