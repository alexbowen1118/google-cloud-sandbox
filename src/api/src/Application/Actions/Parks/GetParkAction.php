<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions\Parks;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;

class GetParkAction extends Action
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response
  {
    $id = (int) $this->resolveArg('par_id');
    
    $parkDAO = $this->DAOFactory->createParkDAO();
    $result = [
      'park' => $parkDAO->getParkById($id)
    ];

    $this->logger->info("Park was viewed.");

    return $this->respondWithData($result);
  }
}
