<?php
declare(strict_types=1);

namespace Tests\Application\Actions\Visitation\Functions;

use DPR\API\Application\Actions\ActionPayload;
use DPR\API\Infrastructure\Persistence\DAO\DAOFactory;
use DPR\API\Infrastructure\Persistence\DAO\FunctionDAO;
use DPR\API\Domain\Models\DeviceFunction;
use DI\Container;
use Tests\TestCase;
use PDOException;

class DeleteFunctionActionTest extends TestCase
{

    public function deleteFunctionCases()
    {
        return array(
            # 1 active function
            array(
                array("fnc_id" => 1, "fnc_name" => "Traffic", "fnc_status" => 1)
            ),
            # 1 active function
            array(
                array("fnc_id" => 1, "fnc_name" => "Trail", "fnc_status" => 1)
            )
        );
    }

    /** 
     * @test 
     * @dataProvider deleteFunctionCases
     */
    public function deleteFunction($functionData)
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $function = new DeviceFunction($functionData);
        $function->setStatus(0);

        $functionDAOProphecy = $this->prophesize(FunctionDAO::class);
        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);

        $daoFactoryProphecy
           ->createFunctionDAO()
           ->willReturn($functionDAOProphecy->reveal())
           ->shouldBeCalledOnce();

        $functionDAOProphecy
            ->deleteFunction(1)
            ->willReturn($function)
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(FunctionDAO::class, $functionDAOProphecy->reveal());

        // Actual Results: Call the DELETE api/visitation/functions/1 endpoint
        $request = $this->createRequest('DELETE', 'api/visitation/functions/1');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();

        // Expected Results: Retrieve correct original data
        $expectedPayload = new ActionPayload(200, ['function' => $function]);
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

        $functionDAOProphecy = $this->prophesize(FunctionDAO::class);
        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);

        $daoFactoryProphecy
           ->createFunctionDAO()
           ->willReturn($functionDAOProphecy->reveal())
           ->shouldBeCalledOnce();

        $functionDAOProphecy
            ->deleteFunction(1)
            ->willReturn(new PDOException("Connection failed: SQLSTATE[HY000] [2002] Connection refused"))
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(FunctionDAO::class, $functionDAOProphecy->reveal());

        // Actual Results: Call the DELETE api/visitation/functions/1 endpoint
        $request = $this->createRequest('DELETE', 'api/visitation/functions/1');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();

        // Expected Results: Retrieve correct original data
        $expectedPayload = new ActionPayload(500, ['message' => "Could not retrieve list of Functions: Connection failed: SQLSTATE[HY000] [2002] Connection refused"]);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}
