<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions\Visitation\Methods;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;
use DPR\API\Domain\Models\Method;

class UpdateMethodAction extends Action
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response
  {
    $id = (int) $this->resolveArg('mtd_id');
    $body = $this->getFormData();
    $name = $body['mtd_name'];

    $method = new Method([
      'mtd_id' => $id,
      'mtd_name' => $name,
      'mtd_status' => 1
    ]);

    $methodDAO = $this->DAOFactory->createMethodDAO();
    $result = [
      'method' => $methodDAO->updateMethod($method)
    ];

    $this->logger->info("Device method was updated.");

    return $this->respondWithData($result);
  }
}
