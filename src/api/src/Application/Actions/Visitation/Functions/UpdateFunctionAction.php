<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions\Visitation\Functions;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;
use DPR\API\Domain\Models\DeviceFunction;

class UpdateFunctionAction extends Action
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response
  {
    $id = (int) $this->resolveArg('fnc_id');
    $body = $this->getFormData();
    $name = $body['fnc_name'];

    $function = new DeviceFunction([
      'fnc_id' => $id,
      'fnc_name' => $name,
      'fnc_status' => 1
    ]);

    $functionDAO = $this->DAOFactory->createFunctionDAO();
    $result = [
      'function' => $functionDAO->updateFunction($function)
    ];

    $this->logger->info("Device function was updated.");

    return $this->respondWithData($result);
  }
}
