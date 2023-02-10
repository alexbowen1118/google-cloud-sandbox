<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions\Visitation\Functions;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;

class DeleteFunctionAction extends Action
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response
  {
    $id = (int) $this->resolveArg('fnc_id');
    
    $functionDAO = $this->DAOFactory->createFunctionDAO();
    $result = [
      'function' => $functionDAO->deleteFunction($id)
    ];

    $this->logger->info("Device function was deleted.");

    return $this->respondWithData($result);
  }
}
