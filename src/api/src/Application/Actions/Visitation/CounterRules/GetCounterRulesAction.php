<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions\Visitation\CounterRules;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;

class GetCounterRulesAction extends Action
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response
  {
    $counterRuleDAO = $this->DAOFactory->createCounterRuleDAO();
    $result = [
      'counter_rules' => $counterRuleDAO->getAllCounterRules()
    ];

    $this->logger->info("Total counter rule list was viewed.");

    return $this->respondWithData($result);
  }
}
