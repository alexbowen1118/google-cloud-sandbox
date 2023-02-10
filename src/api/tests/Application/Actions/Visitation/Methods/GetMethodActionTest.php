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

class GetMethodActionTest extends TestCase
{

    public function getMethodCases()
    {
        return array(
            # 1 active method
            array(
                array("mtd_id" => 1, "mtd_name" => "Automatic", "mtd_status" => 1)
            ),
            # 1 active method
            array(
                array("mtd_id" => 1, "mtd_name" => "Manual", "mtd_status" => 1)
            )
        );
    }

    /** 
     * @test 
     * @dataProvider getMethodCases
     */
    public function getMethod($methodData)
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $method = new Method($methodData);

        $methodDAOProphecy = $this->prophesize(MethodDAO::class);
        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);

        $daoFactoryProphecy
           ->createMethodDAO()
           ->willReturn($methodDAOProphecy->reveal())
           ->shouldBeCalledOnce();

        $methodDAOProphecy
            ->getMethodById(1)
            ->willReturn($method)
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(MethodDAO::class, $methodDAOProphecy->reveal());

        // Actual Results: Call the GET api/visitation/methods/1 endpoint
        $request = $this->createRequest('GET', 'api/visitation/methods/1');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();

        // Expected Results: Retrieve correct original data
        $expectedPayload = new ActionPayload(200, ['method' => $method]);
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
            ->getMethodById(1)
            ->willReturn(new PDOException("Connection failed: SQLSTATE[HY000] [2002] Connection refused"))
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(MethodDAO::class, $methodDAOProphecy->reveal());

        // Actual Results: Call the GET api/visitation/methods/1 endpoint
        $request = $this->createRequest('GET', 'api/visitation/methods/1');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();

        // Expected Results: Retrieve correct original data
        $expectedPayload = new ActionPayload(500, ['message' => "Could not retrieve list of Methods: Connection failed: SQLSTATE[HY000] [2002] Connection refused"]);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}
