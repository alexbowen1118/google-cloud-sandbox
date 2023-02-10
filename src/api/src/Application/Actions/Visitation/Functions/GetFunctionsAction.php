<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions\Visitation\Functions;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;

class GetFunctionsAction extends Action
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response
  {
    $functionDAO = $this->DAOFactory->createFunctionDAO();
    $result = [
      'functions' => $functionDAO->getAllFunctions()
    ];

    $this->logger->info("Device function list was viewed.");

    return $this->respondWithData($result);
  }
}
