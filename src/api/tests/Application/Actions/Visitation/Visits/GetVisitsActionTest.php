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

class GetVisitsActionTest extends TestCase
{

    public function getAllVisitsCases()
    {
        return array(
            # 1 active visit
            array(
                array("vis_id" => 1, "vis_par_id" => 218, "vis_dev_id" => 1, "vis_timestamp" => "2022-01-01 08:00:00", "vis_count" => 15, "vis_count_calculated" => 30, "vis_comments" =>  "I put this in myself", "vis_status" => 1)
            ),
            # 1 inactive visit
            array(
                array("vis_id" => 1, "vis_par_id" => 198, "vis_dev_id" => 3, "vis_timestamp" => "2022-02-01 09:00:00", "vis_count" => 15, "vis_count_calculated" => 30, "vis_comments" =>  "I put this in myself", "vis_status" => 0)
            ),
            # 3 active + nonactive visits
            array(
                array("vis_id" => 1, "vis_par_id" => 198, "vis_dev_id" => 1, "vis_timestamp" => "2022-01-01 08:00:00", "vis_count" => 15, "vis_count_calculated" => 30, "vis_comments" =>  "I put this in myself", "vis_status" => 1),
                array("vis_id" => 2, "vis_par_id" => 198, "vis_dev_id" => 1, "vis_timestamp" => "2022-02-01 09:00:00", "vis_count" => 20, "vis_count_calculated" => 30, "vis_comments" =>  "", "vis_status" => 0),
                array("vis_id" => 3, "vis_par_id" => 240, "vis_dev_id" => 3, "vis_timestamp" => "2022-02-01 12:00:00", "vis_count" => 20, "vis_count_calculated" => 20, "vis_comments" =>  "Raw was correct", "vis_status" => 1)
            ),
            # No visits
            array(array())
        );
    }

    /** 
     * @test 
     * @dataProvider getAllVisitsCases
     */
    public function getAllVisits($visitData)
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $visits = [];
        foreach ($visitData as $data) {
            $visits[] = new Visit($data);
        }

        $visitDAOProphecy = $this->prophesize(VisitDAO::class);
        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);
        
        $daoFactoryProphecy
            ->createVisitDAO()
            ->willReturn($visitDAOProphecy->reveal())
            ->shouldBeCalledOnce();
        
        $visitDAOProphecy
            ->getAllVisits()
            ->willReturn($visits)
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(VisitDAO::class, $visitDAOProphecy->reveal());

        // Actual Results: Call the GET api/visitation/visits endpoint
        $request = $this->createRequest('GET', 'api/visitation/visits');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();

        // Expected Results: Retrieve correct original data
        $expectedPayload = new ActionPayload(200, ['visits' => $visits]);
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
            ->getAllVisits()
            ->willReturn(new PDOException("Connection failed: SQLSTATE[HY000] [2002] Connection refused"))
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(VisitDAO::class, $visitDAOProphecy->reveal());

        // Actual Results: Call the GET api/visitation/visits endpoint
        $request = $this->createRequest('GET', 'api/visitation/visits');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();

        // Expected Results: Retrieve correct original data
        $expectedPayload = new ActionPayload(500, ['message' => "Could not retrieve list of Visits: Connection failed: SQLSTATE[HY000] [2002] Connection refused"]);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}
