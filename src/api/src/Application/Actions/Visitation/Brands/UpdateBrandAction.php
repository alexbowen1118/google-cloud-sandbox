<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions\Visitation\Brands;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;
use DPR\API\Domain\Models\Brand;

class UpdateBrandAction extends Action
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response
  {
    $id = (int) $this->resolveArg('brn_id');
    $body = $this->getFormData();
    $name = $body['brn_name'];

    $brand = new Brand([
      'brn_id' => $id,
      'brn_name' => $name,
      'brn_status' => 1
    ]);

    $brandDAO = $this->DAOFactory->createBrandDAO();
    $result = [
      'brand' => $brandDAO->updateBrand($brand)
    ];

    $this->logger->info("Device brand was updated.");

    return $this->respondWithData($result);
  }
}
