<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions\Visitation\CounterRules;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;
use DPR\API\Domain\Models\CounterRule;

class UpdateCounterRuleAction extends Action
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response
  {
    $id = (int) $this->resolveArg('rul_id');
    $body = $this->getFormData();
    $dev_id = $body['rul_dev_id'];
    $start = $body['rul_start'];
    $end = $body['rul_end'];
    $multiplier = $body['rul_multiplier'];

    $counter_rule = new CounterRule([
      'rul_id' => $id,
      'rul_dev_id' => $dev_id,
      'rul_start' => $start,
      'rul_end' => $end,
      'rul_multiplier' => $multiplier,
      'rul_status' => 1
    ]);

    $counterRuleDAO = $this->DAOFactory->createCounterRuleDAO();
    $result = [
      'counter_rule' => $counterRuleDAO->updateCounterRule($counter_rule)
    ];

    $this->logger->info("Device counter rule was updated.");

    return $this->respondWithData($result);
  }
}
