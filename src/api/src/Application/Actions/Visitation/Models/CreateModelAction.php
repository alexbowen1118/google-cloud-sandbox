<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions\Visitation\Models;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;
use DPR\API\Domain\Models\Model;

class CreateModelAction extends Action
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response
  {
    $body = $this->getFormData();
    $name = $body['mdl_name'];

    $model = new Model([
      'mdl_id' => NULL,
      'mdl_name' => $name,
      'mdl_status' => 1
    ]);

    $modelDAO = $this->DAOFactory->createModelDAO();
    $result = [
      'model' => $modelDAO->createModel($model)
    ];

    $this->logger->info("Device model was created.");

    return $this->respondWithData($result);
  }
}
