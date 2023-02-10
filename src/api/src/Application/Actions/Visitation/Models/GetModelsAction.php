<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions\Visitation\Models;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;

class GetModelsAction extends Action
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response
  {
    $modelDAO = $this->DAOFactory->createModelDAO();
    $result = [
      'models' => $modelDAO->getAllModels()
    ];

    $this->logger->info("Device model list was viewed.");

    return $this->respondWithData($result);
  }
}
