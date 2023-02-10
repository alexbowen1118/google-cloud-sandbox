<?php
declare(strict_types=1);

namespace Tests\Application\Actions\Visitation\Visits;

use DPR\API\Application\Actions\ActionPayload;
use DPR\API\Infrastructure\Persistence\DAO\DAOFactory;
use DPR\API\Infrastructure\Persistence\DAO\VisitDAO;
use DPR\API\Domain\Models\Visit;
use DI\Container;
use Tests\TestCase;
use PDOException;

class UpdateVisitActionTest extends TestCase
{

    public function createVisitCases()
    {
        return array(
            # 1 active visit
            array(
                array("vis_id" => 1, "vis_par_id" => 218, "vis_dev_id" => 1, "vis_timestamp" => "2022-01-01 08:00:00", "vis_count" => 15, "vis_count_calculated" => 30, "vis_comments" =>  "I put this in myself", "vis_status" => 1)
            ),
            # 1 active visit
            array(
                array("vis_id" => 1, "vis_par_id" => 198, "vis_dev_id" => 3, "vis_timestamp" => "2022-02-01 09:00:00", "vis_count" => 15, "vis_count_calculated" => 30, "vis_comments" =>  "I put this in myself", "vis_status" => 1)
            )
        );
    }

    /** 
     * @test 
     * @dataProvider createVisitCases
     */
    public function createVisit($visitData)
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $visit = new Visit($visitData);
        $visit->setCountCalculated(55);

        $visitDAOProphecy = $this->prophesize(VisitDAO::class);
        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);

        $daoFactoryProphecy
           ->createVisitDAO()
           ->willReturn($visitDAOProphecy->reveal())
           ->shouldBeCalledOnce();

        $visitDAOProphecy
            ->updateVisit()
            ->willReturn($visit)
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(VisitDAO::class, $visitDAOProphecy->reveal());

        // Actual Results: Call the PUT api/visitation/visits/1 endpoint
        $request = $this->createRequest('PUT', 'api/visitation/visits/1');
        
        $body = [
            'vis_par_id' => $visit->getParId(),
            'vis_dev_id' => $visit->getDevId(),
            'vis_timestamp' => $visit->getTimestamp(),
            'vis_count' => $visit->getCount(),
            'vis_count_calculated' => $visit->getCountCalculated(),
            'vis_comments' => $visit->getComments()
        ];
        $request = $request->withParsedBody($body);
        $response = $app->handle($request);

        $payload = (string) $response->getBody();

        // Expected Results: Retrieve correct original data
        $expectedPayload = new ActionPayload(200, ['visit' => $visit]);
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

        $visitDAOProphecy = $this->prophesize(VisitDAO::class);
        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);

        $daoFactoryProphecy
           ->createVisitDAO()
           ->willReturn($visitDAOProphecy->reveal())
           ->shouldBeCalledOnce();

        $visitDAOProphecy
            ->updateVisit()
            ->willReturn(new PDOException("Connection failed: SQLSTATE[HY000] [2002] Connection refused"))
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(VisitDAO::class, $visitDAOProphecy->reveal());

        // Actual Results: Call the PUT api/visitation/visits/1 endpoint
        $request = $this->createRequest('PUT', 'api/visitation/visits/1');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();
 
         // Expected Results: Retrieve correct original data
         $expectedPayload = new ActionPayload(500, ['message' => "Could not retrieve list of Visits: Connection failed: SQLSTATE[HY000] [2002] Connection refused"]);
         $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}
