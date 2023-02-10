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

class CreateMethodActionTest extends TestCase
{

    public function createMethodCases()
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
     * @dataProvider createMethodCases
     */
    public function createMethod($methodData)
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
            ->createMethod()
            ->willReturn($method)
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(MethodDAO::class, $methodDAOProphecy->reveal());

        // Actual Results: Call the POST api/visitation/methods endpoint
        $request = $this->createRequest('POST', 'api/visitation/methods');
        
        $body = ['mtd_name' => $method->getName()];
        $request = $request->withParsedBody($body);
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
            ->createMethod()
            ->willReturn(new PDOException("Connection failed: SQLSTATE[HY000] [2002] Connection refused"))
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(MethodDAO::class, $methodDAOProphecy->reveal());

        // Actual Results: Call the POST api/visitation/methods endpoint
        $request = $this->createRequest('POST', 'api/visitation/methods');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();
 
         // Expected Results: Retrieve correct original data
         $expectedPayload = new ActionPayload(500, ['message' => "Could not retrieve list of Methods: Connection failed: SQLSTATE[HY000] [2002] Connection refused"]);
         $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}
