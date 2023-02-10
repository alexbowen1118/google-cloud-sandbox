<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions\Parks;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;

class GetParkIdByCodeAction extends Action
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response
  {
    $code = $this->resolveArg('par_code');
    
    $parkDAO = $this->DAOFactory->createParkDAO();
    $result = [
      'par_id' => $parkDAO->getParkIdByCode($code)
    ];

    $this->logger->info("Park ID was retrieved.");

    return $this->respondWithData($result);
  }
}
