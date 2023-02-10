<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions\Visitation\Visits;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;
use DPR\API\Domain\Models\Visit;

class CreateVisitAction extends Action
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response
  {
    

    $body = $this->getFormData();
    $par_id = $body['vis_par_id'];
    $dev_id = $body['vis_dev_id'];
    $timestamp = $body['vis_timestamp'];
    $count = $body['vis_count'];
    $count_calculated = $count;
    $comments = $body['vis_comments'];

    $deviceDAO = $this->DAOFactory->createDeviceDAO();
    $device = $deviceDAO->getDeviceById($dev_id);
    $count_calculated = $device->getMultiplier() * $count;

    $counterRuleDAO = $this->DAOFactory->createCounterRuleDAO();
    $counter_rules = $counterRuleDAO->getCounterRulesByDevice($dev_id);

    foreach ($counter_rules as $counter_rule) {
      $start = $counter_rule->getStart();
      $end = $counter_rule->getEnd();
      $startUnix = strtotime($start);
      $endUnix = strtotime($end);
      $timestampUnix = strtotime($timestamp);
      $start = strtotime(date('Y', $timestampUnix) . '-' . date('m-d H:i:s', $startUnix));
      $end = strtotime(date('Y', $timestampUnix) . '-' . date('m-d H:i:s', $endUnix));
      if ($timestamp >= $start && $timestamp <= $end) {
        $multiplier = $counter_rule->getMultiplier();
        $count_calculated = $multiplier * $count;
        break;
      }
    }

    $visit = new Visit([
      'vis_id' => NULL,
      'vis_par_id' => $par_id,
      'vis_dev_id' => $dev_id,
      'vis_timestamp' => $timestamp,
      'vis_count' => $count,
      'vis_count_calculated' => $count_calculated,
      'vis_comments' => $comments,
      'vis_status' => 1
    ]);

    $visitDAO = $this->DAOFactory->createVisitDAO();
    $result = [
      'visit' => $visitDAO->createVisit($visit)
    ];

    $this->logger->info("Device visit was created.");

    return $this->respondWithData($result);
  }
}
