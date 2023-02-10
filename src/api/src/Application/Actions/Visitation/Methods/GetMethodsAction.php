<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions\Visitation\Methods;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;

class GetMethodsAction extends Action
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response
  {
    $methodDAO = $this->DAOFactory->createMethodDAO();
    $result = [
      'methods' => $methodDAO->getAllMethods()
    ];

    $this->logger->info("Device method list was viewed.");

    return $this->respondWithData($result);
  }
}
