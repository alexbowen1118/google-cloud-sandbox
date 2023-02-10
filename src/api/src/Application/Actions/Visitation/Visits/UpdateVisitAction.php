<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions\Visitation\Visits;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;
use DPR\API\Domain\Models\Visit;

class UpdateVisitAction extends Action
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response
  {
    $id = $this->resolveArg('vis_id');
    $body = $this->getFormData();
    $par_id = $body['vis_par_id'];
    $dev_id = $body['vis_dev_id'];
    $timestamp = $body['vis_timestamp'];
    $count = $body['vis_count'];
    $count_calculated = $body['vis_count_calculated'];
    $comments = $body['vis_comments'];

    $visit = new Visit([
      'vis_id' => $id,
      'vis_par_id' => $par_id,
      'vis_dev_id' => $dev_id,
      'vis_timestamp' => $timestamp,
      'vis_count' => $count,
      'vis_count_calculated' => $count_calculated,
      'vis_comments' => $comments,
      'vis_status' => 1
    ]);
    $result = [
      'visit' => $this->DAOFactory->VisitDAO->updateVisit($visit)
    ];

    $this->logger->info("Device visit was updated.");

    return $this->respondWithData($result);
  }
}
