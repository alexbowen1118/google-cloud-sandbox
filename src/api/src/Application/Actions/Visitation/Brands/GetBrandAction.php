<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions\Visitation\Brands;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;

class GetBrandAction extends Action
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response
  {
    $id = (int) $this->resolveArg('brn_id');
    
    $brandDAO = $this->DAOFactory->createBrandDAO();
    $result = [
      'brand' => $brandDAO->getBrandById($id)
    ];

    $this->logger->info("Device brand was viewed.");

    return $this->respondWithData($result);
  }
}
