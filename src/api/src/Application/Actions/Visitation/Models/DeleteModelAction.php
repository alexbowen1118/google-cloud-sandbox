<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions\Visitation\Models;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;

class DeleteModelAction extends Action
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response
  {
    $id = (int) $this->resolveArg('mdl_id');
    
    $modelDAO = $this->DAOFactory->createModelDAO();
    $result = [
      'model' => $modelDAO->deleteModel($id)
    ];

    $this->logger->info("Device model was deleted.");

    return $this->respondWithData($result);
  }
}
