<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions\Visitation\Brands;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;
use DPR\API\Domain\Models\Brand;

class CreateBrandAction extends Action
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response
  {
    $body = $this->getFormData();
    $name = $body['brn_name'];

    $brand = new Brand([
      'brn_id' => NULL,
      'brn_name' => $name,
      'brn_status' => 1
    ]);

    $brandDAO = $this->DAOFactory->createBrandDAO();
    $result = [
      'brand' => $brandDAO->createBrand($brand)
    ];

    $this->logger->info("Device brand was created.");

    return $this->respondWithData($result);
  }
}
