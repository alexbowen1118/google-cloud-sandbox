<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions\Visitation\Types;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;
use DPR\API\Domain\Models\Type;

class UpdateTypeAction extends Action
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response
  {
    $id = (int)$this->resolveArg('typ_id');
    $body = $this->getFormData();
    $name = $body['typ_name'];

    $type = new Type([
      'typ_id' => $id,
      'typ_name' => $name,
      'typ_status' => 1
    ]);

    $typeDAO = $this->DAOFactory->createTypeDAO();
    $result = [
      'type' => $typeDAO->updateType($type)
    ];

    $this->logger->info("Device type was updated.");

    return $this->respondWithData($result);
  }
}
