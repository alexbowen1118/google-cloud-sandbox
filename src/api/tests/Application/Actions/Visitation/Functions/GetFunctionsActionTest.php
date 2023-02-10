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

class GetFunctionsActionTest extends TestCase
{

    public function getAllFunctionsCases()
    {
        return array(
            # 1 active function
            array(
                array("fnc_id" => 1, "fnc_name" => "Traffic", "fnc_status" => 1)
            ),
            # 1 inactive function
            array(
                array("fnc_id" => 1, "fnc_name" => "Trail", "fnc_status" => 0)
            ),
            # 3 active + nonactive functions
            array(
                array("fnc_id" => 1, "fnc_name" => "Traffic", "fnc_status" => 1),
                array("fnc_id" => 2, "fnc_name" => "Trail", "fnc_status" => 0),
                array("fnc_id" => 3, "fnc_name" => "Visitor Center", "fnc_status" => 1)
            ),
            # No functions
            array(array())
        );
    }

    /** 
     * @test 
     * @dataProvider getAllFunctionsCases
     */
    public function getAllFunctions($functionData)
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $functions = [];
        foreach ($functionData as $data) {
            $functions[] = new DeviceFunction($data);
        }

        $functionDAOProphecy = $this->prophesize(FunctionDAO::class);
        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);
        
        $daoFactoryProphecy
            ->createFunctionDAO()
            ->willReturn($functionDAOProphecy->reveal())
            ->shouldBeCalledOnce();
        
        $functionDAOProphecy
            ->getAllFunctions()
            ->willReturn($functions)
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(FunctionDAO::class, $functionDAOProphecy->reveal());

        // Actual Results: Call the GET api/visitation/functions endpoint
        $request = $this->createRequest('GET', 'api/visitation/functions');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();

        // Expected Results: Retrieve correct original data
        $expectedPayload = new ActionPayload(200, ['functions' => $functions]);
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
            ->getAllFunctions()
            ->willReturn(new PDOException("Connection failed: SQLSTATE[HY000] [2002] Connection refused"))
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(FunctionDAO::class, $functionDAOProphecy->reveal());

        // Actual Results: Call the GET api/visitation/functions endpoint
        $request = $this->createRequest('GET', 'api/visitation/functions');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();

        // Expected Results: Retrieve correct original data
        $expectedPayload = new ActionPayload(500, ['message' => "Could not retrieve list of Functions: Connection failed: SQLSTATE[HY000] [2002] Connection refused"]);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}
