<?php
declare(strict_types=1);

namespace Tests\Application\Actions\Visitation\Methods;

use DPR\API\Application\Actions\ActionPayload;
use DPR\API\Infrastructure\Persistence\DAO\DAOFactory;
use DPR\API\Infrastructure\Persistence\DAO\MethodDAO;
use DPR\API\Domain\Models\Method;
use DI\Container;
use Tests\TestCase;
use PDOException;

class GetMethodsActionTest extends TestCase
{

    public function getAllMethodsCases()
    {
        return array(
            # 1 active method
            array(
                array("mtd_id" => 1, "mtd_name" => "Automatic", "mtd_status" => 1)
            ),
            # 1 inactive method
            array(
                array("mtd_id" => 1, "mtd_name" => "Manual", "mtd_status" => 0)
            ),
            # 3 active + nonactive methods
            array(
                array("mtd_id" => 1, "mtd_name" => "Automatic", "mtd_status" => 1),
                array("mtd_id" => 2, "mtd_name" => "Manual", "mtd_status" => 0),
                array("mtd_id" => 3, "mtd_name" => "Mixed", "mtd_status" => 1)
            ),
            # No methods
            array(array())
        );
    }

    /** 
     * @test 
     * @dataProvider getAllMethodsCases
     */
    public function getAllMethods($methodData)
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $methods = [];
        foreach ($methodData as $data) {
            $methods[] = new Method($data);
        }

        $methodDAOProphecy = $this->prophesize(MethodDAO::class);
        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);
        
        $daoFactoryProphecy
            ->createMethodDAO()
            ->willReturn($methodDAOProphecy->reveal())
            ->shouldBeCalledOnce();
        
        $methodDAOProphecy
            ->getAllMethods()
            ->willReturn($methods)
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(MethodDAO::class, $methodDAOProphecy->reveal());

        // Actual Results: Call the GET api/visitation/methods endpoint
        $request = $this->createRequest('GET', 'api/visitation/methods');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();

        // Expected Results: Retrieve correct original data
        $expectedPayload = new ActionPayload(200, ['methods' => $methods]);
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

        $methodDAOProphecy = $this->prophesize(MethodDAO::class);
        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);

        $daoFactoryProphecy
           ->createMethodDAO()
           ->willReturn($methodDAOProphecy->reveal())
           ->shouldBeCalledOnce();

        $methodDAOProphecy
            ->getAllMethods()
            ->willReturn(new PDOException("Connection failed: SQLSTATE[HY000] [2002] Connection refused"))
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(MethodDAO::class, $methodDAOProphecy->reveal());

        // Actual Results: Call the GET api/visitation/methods endpoint
        $request = $this->createRequest('GET', 'api/visitation/methods');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();

        // Expected Results: Retrieve correct original data
        $expectedPayload = new ActionPayload(500, ['message' => "Could not retrieve list of Methods: Connection failed: SQLSTATE[HY000] [2002] Connection refused"]);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}
