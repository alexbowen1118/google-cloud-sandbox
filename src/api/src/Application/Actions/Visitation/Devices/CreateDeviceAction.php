<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions\Visitation\Devices;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;
use DPR\API\Domain\Models\Device;

class CreateDeviceAction extends Action
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response
  {
    $body = $this->getFormData();
    $par_id = $body['dev_par_id'];
    $number = $body['dev_number'];
    $name = $body['dev_name'];
    $function = $body['dev_function'];
    $type = $body['dev_type'];
    $method = $body['dev_method'];
    $model = $body['dev_model'];
    $brand = $body['dev_brand'];
    $multiplier = $body['dev_multiplier'];
    $lat = $body['dev_lat'];
    $lon = $body['dev_lon'];
    $seeinsight_id = $body['dev_seeinsight_id'];
    $date_uploaded = $body['dev_date_uploaded'];


    $device = new Device([
      'dev_id' => NULL,
      'dev_par_id' => $par_id,
      'dev_number' => $number,
      'dev_name' => $name,
      'dev_function' => $function,
      'dev_type' => $type,
      'dev_method' => $method,
      'dev_model' => $model,
      'dev_brand' => $brand,
      'dev_multiplier' => $multiplier,
      'dev_lat' => $lat,
      'dev_lon' => $lon,
      'dev_seeinsight_id' => $seeinsight_id,
        'dev_status' => 1,
      'dev_date_uploaded' => $date_uploaded
    ]);

    $deviceDAO = $this->DAOFactory->createDeviceDAO();
    $result = [
      'device' => $deviceDAO->createDevice($device)
    ];

    $this->logger->info("Device was created.");

    return $this->respondWithData($result);
  }
}
