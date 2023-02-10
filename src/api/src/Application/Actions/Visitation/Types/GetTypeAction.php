<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions\Visitation\Types;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;

class GetTypeAction extends Action
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response
  {
    $id = (int) $this->resolveArg('typ_id');
    
    $typeDAO = $this->DAOFactory->createTypeDAO();
    $result = [
      'type' => $typeDAO->getTypeById($id)
    ];

    $this->logger->info("Device type was viewed.");

    return $this->respondWithData($result);
  }
}
