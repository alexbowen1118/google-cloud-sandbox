<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions\Visitation\Visits;

use DPR\API\Domain\Ubidots\UbidotsException;
use http\Message;
use PHPUnit\Exception;
use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;
use DPR\API\Domain\Models\Visit;

class CreateVisitFromWebhookAction extends Action
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $pdao = $this->DAOFactory->createParkDAO();
        $ddao = $this->DAOFactory->createDeviceDAO();
        $vdao = $this->DAOFactory->createVisitDAO();

        $body = file_get_contents('php://input');
        $result = [];
        $webhook = json_decode($body, true);

        try {
            $raw_timestamp = new \DateTime($webhook['datetime'], new \DateTimeZone('America/New_York'));
            $timestamp = $raw_timestamp->format("Y-m-d H:i:s");
        } catch(\Exception $e){
            throw new UbidotsException($e->getMessage());
        }

        file_put_contents('php://stdout', 'Webhook event received: ' . print_r($webhook, true) . "\r\n");
        $this->logger->info('Webhook event received: ' . print_r($webhook, true));

        $visit = null;
        $dev_seeinsight_id = $webhook['seeinsight_id'];
        $parsedname = explode("-", $webhook['name']);
        $par_id = null;
        if (count($parsedname) == 5) {
            $par_id = $parsedname[2];

            $visit = new Visit(array(
                'vis_id' => NULL,
                'vis_par_id' => $par_id,
                'vis_dev_id' => NULL,
                'vis_timestamp' => $timestamp,
                'vis_count' => $webhook['value'],
                'vis_count_calculated' => $webhook['value'],
                'vis_comments' => $webhook['seeinsight_id'],
                'vis_status' => 1
            ));

            $visit->setParId($pdao->getParkIdByCode($visit->getParId()));
            $si_id = $visit->getComments();
            $id = $ddao->getDeviceIdBySeeInsightsId($si_id);
            $visit->setDevId($id);
            $visit->setComments("");
            $result[] = $vdao->createVisit($visit);
            $this->logger->info("Visit created from webhook event. " . "DEVICE: " . $webhook['name']);
        }
           else {
                throw new UbidotsException("Invalid device name.");
           }

        return $this->respondWithData($result);
    }
}
