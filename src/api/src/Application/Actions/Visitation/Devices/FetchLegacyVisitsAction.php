<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions\Visitation\Devices;

use Psr\Http\Message\ResponseInterface as Response;
use DPR\API\Application\Actions\Visitation\VisitationAction;

class FetchLegacyVisitsAction extends VisitationAction
{
  /**
   * {@inheritdoc}
   */
  //make sure to change to protected after testing
  public function action(): Response
  {
    $data = null;
    $pdao = $this->DAOFactory->createParkDAO();
    $ddao = $this->DAOFactory->createDeviceDAO();
    $vdao = $this->DAOFactory->createVisitDAO();
    $result = [];

    $data = $this->ubidotsAPI->fetchLegacyUbidotsData("v");
    foreach($data as $visit) {
        $visit->setParId($pdao->getParkIdByCode($visit->getParId()));
        $visit->setTimestamp(date("Y-m-d H:i:s", (int)ceil($visit->getTimestamp()/1000)));
        $id = $ddao->getDeviceIdBySeeInsightsId($visit->getComments());
        $visit->setDevId($id);
        $visit->setComments("");
        $result = $vdao->createVisit($visit);
    }

    $this->logger->info("Data fetched successfully.");

    return $this->respondWithData((array)$result);
  }
}
