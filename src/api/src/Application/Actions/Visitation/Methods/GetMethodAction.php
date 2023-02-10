<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions\Visitation\Methods;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;

class GetMethodAction extends Action
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response
  {
    $id = (int) $this->resolveArg('mtd_id');
    
    $methodDAO = $this->DAOFactory->createMethodDAO();
    $result = [
      'method' => $methodDAO->getMethodById($id)
    ];

    $this->logger->info("Device method was viewed.");

    return $this->respondWithData($result);
  }
}
