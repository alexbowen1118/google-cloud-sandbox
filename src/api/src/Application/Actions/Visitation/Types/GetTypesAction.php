<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions\Visitation\Types;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;

class GetTypesAction extends Action
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response
  {
    $typeDAO = $this->DAOFactory->createTypeDAO();
    $result = [
      'types' => $typeDAO->getAllTypes()
    ];

    $this->logger->info("Device type list was viewed.");

    return $this->respondWithData($result);
  }
}
