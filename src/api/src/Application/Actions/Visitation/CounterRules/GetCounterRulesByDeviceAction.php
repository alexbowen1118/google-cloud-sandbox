<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions\Visitation\CounterRules;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;

class GetCounterRulesByDeviceAction extends Action
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response
  {
    $device = $this->resolveArg('dev_id');

    $counterRuleDAO = $this->DAOFactory->createCounterRuleDAO();
    $result = [
      'counter_rules' => $counterRuleDAO->getCounterRulesByDevice($device)
    ];

    $this->logger->info("Device counter rule list was viewed.");

    return $this->respondWithData($result);
  }
}
