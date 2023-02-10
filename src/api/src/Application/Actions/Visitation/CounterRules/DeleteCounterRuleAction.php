<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions\Visitation\CounterRules;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;

class DeleteCounterRuleAction extends Action
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response
  {
    $id = (int) $this->resolveArg('rul_id');
    
    $counterRuleDAO = $this->DAOFactory->createCounterRuleDAO();
    $result = [
      'counter_rule' => $counterRuleDAO->deleteCounterRule($id)
    ];

    $this->logger->info("Device counter rule was deleted.");

    return $this->respondWithData($result);
  }
}
