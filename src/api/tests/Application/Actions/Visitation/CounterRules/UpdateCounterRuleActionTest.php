<?php
declare(strict_types=1);

namespace Tests\Application\Actions\Visitation\CounterRules;

use DPR\API\Application\Actions\ActionPayload;
use DPR\API\Infrastructure\Persistence\DAO\DAOFactory;
use DPR\API\Infrastructure\Persistence\DAO\CounterRuleDAO;
use DPR\API\Domain\Models\CounterRule;
use DI\Container;
use Tests\TestCase;
use PDOException;

class UpdateCounterRuleActionTest extends TestCase
{

    public function createCounterRuleCases()
    {
        return array(
            # 1 active counter rule
            array(
                array("rul_id" => 1, "rul_dev_id" => 1, "rul_start" => "2022-01-01 00:00:00", "rul_end" => "2022-05-01 00:00:00", "rul_multiplier" => 2.0, "rul_status" => 1)
            ),
            # 1 active counter rule
            array(
                array("rul_id" => 1, "rul_dev_id" => 1, "rul_start" => "2022-05-01 00:00:00", "rul_end" => "2022-08-01 00:00:00", "rul_multiplier" => 4.0, "rul_status" => 1)
            )
        );
    }

    /** 
     * @test 
     * @dataProvider createCounterRuleCases
     */
    public function createCounterRule($counter_ruleData)
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $counter_rule = new CounterRule($counter_ruleData);
        $counter_rule->setMultiplier(8.5);

        $counter_ruleDAOProphecy = $this->prophesize(CounterRuleDAO::class);
        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);

        $daoFactoryProphecy
           ->createCounterRuleDAO()
           ->willReturn($counter_ruleDAOProphecy->reveal())
           ->shouldBeCalledOnce();

        $counter_ruleDAOProphecy
            ->updateCounterRule()
            ->willReturn($counter_rule)
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(CounterRuleDAO::class, $counter_ruleDAOProphecy->reveal());

        // Actual Results: Call the PUT api/visitation/counter_rules/1 endpoint
        $request = $this->createRequest('PUT', 'api/visitation/counter_rules/1');
        
        $body = [
            'rul_dev_id' => $counter_rule->getDevId(),
            'rul_start' => $counter_rule->getStart(),
            'rul_end' => $counter_rule->getEnd(),
            'rul_multiplier' => $counter_rule->getMultiplier()
        ];
        $request = $request->withParsedBody($body);
        $response = $app->handle($request);

        $payload = (string) $response->getBody();

        // Expected Results: Retrieve correct original data
        $expectedPayload = new ActionPayload(200, ['counter_rule' => $counter_rule]);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }

    /** 
     * @test
     */
    public function willReturnServerError()
    {
        $app = $this->getAppInstance();
        /** @var Container $container */
        $container = $app->getContainer();

        $counter_ruleDAOProphecy = $this->prophesize(CounterRuleDAO::class);
        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);

        $daoFactoryProphecy
           ->createCounterRuleDAO()
           ->willReturn($counter_ruleDAOProphecy->reveal())
           ->shouldBeCalledOnce();

        $counter_ruleDAOProphecy
            ->updateCounterRule()
            ->willReturn(new PDOException("Connection failed: SQLSTATE[HY000] [2002] Connection refused"))
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(CounterRuleDAO::class, $counter_ruleDAOProphecy->reveal());

        // Actual Results: Call the PUT api/visitation/counter_rules/1 endpoint
        $request = $this->createRequest('PUT', 'api/visitation/counter_rules/1');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();
 
         // Expected Results: Retrieve correct original data
         $expectedPayload = new ActionPayload(500, ['message' => "Could not retrieve list of CounterRules: Connection failed: SQLSTATE[HY000] [2002] Connection refused"]);
         $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}
