<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions\Visitation\Functions;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;

class GetFunctionAction extends Action
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response
  {
    $id = (int) $this->resolveArg('fnc_id');
    
    $functionDAO = $this->DAOFactory->createFunctionDAO();
    $result = [
      'function' => $functionDAO->getFunctionById($id)
    ];

    $this->logger->info("Device function was viewed.");

    return $this->respondWithData($result);
  }
}
