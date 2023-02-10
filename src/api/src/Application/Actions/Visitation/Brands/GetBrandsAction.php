<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions\Visitation\Brands;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;

class GetBrandsAction extends Action
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response
  {
    $brandDAO = $this->DAOFactory->createBrandDAO();
    $result = [
      'brands' => $brandDAO->getAllBrands()
    ];

    $this->logger->info("Device brand list was viewed.");

    return $this->respondWithData($result);
  }
}
