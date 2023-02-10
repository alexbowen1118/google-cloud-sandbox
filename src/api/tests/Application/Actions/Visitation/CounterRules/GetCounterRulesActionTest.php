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

class GetCounterRulesActionTest extends TestCase
{

    public function getAllCounterRulesCases()
    {
        return array(
            # 1 active counter rule
            array(
                array("rul_id" => 1, "rul_dev_id" => 1, "rul_start" => "2022-01-01 00:00:00", "rul_end" => "2022-05-01 00:00:00", "rul_multiplier" => 2.0, "rul_status" => 1)
            ),
            # 1 inactive counter rule
            array(
                array("rul_id" => 1, "rul_dev_id" => 1, "rul_start" => "2022-05-01 00:00:00", "rul_end" => "2022-08-01 00:00:00", "rul_multiplier" => 4.0, "rul_status" => 0)
            ),
            # 3 active + nonactive counter rule
            array(
                array("rul_id" => 1, "rul_dev_id" => 1, "rul_start" => "2022-01-01 00:00:00", "rul_end" => "2022-05-01 00:00:00", "rul_multiplier" => 2.0, "rul_status" => 1),
                array("rul_id" => 2, "rul_dev_id" => 1, "rul_start" => "2022-05-01 00:00:00", "rul_end" => "2022-08-01 00:00:00", "rul_multiplier" => 4.0, "rul_status" => 0),
                array("rul_id" => 3, "rul_dev_id" => 3, "rul_start" => "2022-01-01 00:00:00", "rul_end" => "2022-07-01 00:00:00", "rul_multiplier" => 10.0, "rul_status" => 1)
            ),
            # No visits
            array(array())
        );
    }

    /** 
     * @test 
     * @dataProvider getAllCounterRulesCases
     */
    public function getAllCounterRules($counter_ruleData)
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $counter_rules = [];
        foreach ($counter_ruleData as $data) {
            $counter_rules[] = new CounterRule($data);
        }

        $counter_ruleDAOProphecy = $this->prophesize(CounterRuleDAO::class);
        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);
        
        $daoFactoryProphecy
            ->createCounterRuleDAO()
            ->willReturn($counter_ruleDAOProphecy->reveal())
            ->shouldBeCalledOnce();
        
        $counter_ruleDAOProphecy
            ->getAllCounterRules()
            ->willReturn($counter_rules)
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(CounterRuleDAO::class, $counter_ruleDAOProphecy->reveal());

        // Actual Results: Call the GET api/visitation/counter_rules endpoint
        $request = $this->createRequest('GET', 'api/visitation/counter_rules');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();

        // Expected Results: Retrieve correct original data
        $expectedPayload = new ActionPayload(200, ['counter_rules' => $counter_rules]);
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
            ->getAllCounterRules()
            ->willReturn(new PDOException("Connection failed: SQLSTATE[HY000] [2002] Connection refused"))
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(CounterRuleDAO::class, $counter_ruleDAOProphecy->reveal());

        // Actual Results: Call the GET api/visitation/counter_rules endpoint
        $request = $this->createRequest('GET', 'api/visitation/counter_rules');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();

        // Expected Results: Retrieve correct original data
        $expectedPayload = new ActionPayload(500, ['message' => "Could not retrieve list of CounterRules: Connection failed: SQLSTATE[HY000] [2002] Connection refused"]);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}
