<?php
declare(strict_types=1);

namespace Tests\Application\Actions\Visitation\Models;

use DPR\API\Application\Actions\ActionPayload;
use DPR\API\Infrastructure\Persistence\DAO\DAOFactory;
use DPR\API\Infrastructure\Persistence\DAO\ModelDAO;
use DPR\API\Domain\Models\Model;
use DI\Container;
use Tests\TestCase;
use PDOException;

class CreateModelActionTest extends TestCase
{

    public function createModelCases()
    {
        return array(
            # 1 active model
            array(
                array("mdl_id" => 1, "mdl_name" => "Core", "mdl_status" => 1)
            ),
            # 1 active model
            array(
                array("mdl_id" => 1, "mdl_name" => "Photon", "mdl_status" => 1)
            )
        );
    }

    /** 
     * @test 
     * @dataProvider createModelCases
     */
    public function createModel($modelData)
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $model = new Model($modelData);

        $modelDAOProphecy = $this->prophesize(ModelDAO::class);
        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);

        $daoFactoryProphecy
           ->createModelDAO()
           ->willReturn($modelDAOProphecy->reveal())
           ->shouldBeCalledOnce();

        $modelDAOProphecy
            ->createModel()
            ->willReturn($model)
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(ModelDAO::class, $modelDAOProphecy->reveal());

        // Actual Results: Call the POST api/visitation/models endpoint
        $request = $this->createRequest('POST', 'api/visitation/models');
        
        $body = ['mdl_name' => $model->getName()];
        $request = $request->withParsedBody($body);
        $response = $app->handle($request);

        $payload = (string) $response->getBody();

        // Expected Results: Retrieve correct original data
        $expectedPayload = new ActionPayload(200, ['model' => $model]);
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

        $modelDAOProphecy = $this->prophesize(ModelDAO::class);
        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);

        $daoFactoryProphecy
           ->createModelDAO()
           ->willReturn($modelDAOProphecy->reveal())
           ->shouldBeCalledOnce();

        $modelDAOProphecy
            ->createModel()
            ->willReturn(new PDOException("Connection failed: SQLSTATE[HY000] [2002] Connection refused"))
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(ModelDAO::class, $modelDAOProphecy->reveal());

        // Actual Results: Call the POST api/visitation/models endpoint
        $request = $this->createRequest('POST', 'api/visitation/models');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();
 
         // Expected Results: Retrieve correct original data
         $expectedPayload = new ActionPayload(500, ['message' => "Could not retrieve list of Models: Connection failed: SQLSTATE[HY000] [2002] Connection refused"]);
         $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}
