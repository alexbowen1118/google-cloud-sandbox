<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions\Visitation\Models;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;

class GetModelAction extends Action
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response
  {
    $id = (int) $this->resolveArg('mdl_id');
    
    $modelDAO = $this->DAOFactory->createModelDAO();
    $result = [
      'model' => $modelDAO->getModelById($id)
    ];

    $this->logger->info("Device model was viewed.");

    return $this->respondWithData($result);
  }
}
