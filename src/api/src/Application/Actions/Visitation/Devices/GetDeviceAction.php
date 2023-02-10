<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions\Visitation\Devices;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;

class GetDeviceAction extends Action
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response
  {
    $id = (int) $this->resolveArg('dev_id');
    
    $deviceDAO = $this->DAOFactory->createDeviceDAO();
    $result = [
      'device' => $deviceDAO->getDeviceById($id)
    ];

    $this->logger->info("Device was viewed.");

    return $this->respondWithData($result);
  }
}
