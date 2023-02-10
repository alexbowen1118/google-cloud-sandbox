<?php
declare(strict_types=1);

namespace Tests\Application\Actions\Visitation\Types;

use DPR\API\Application\Actions\ActionPayload;
use DPR\API\Infrastructure\Persistence\DAO\DAOFactory;
use DPR\API\Infrastructure\Persistence\DAO\TypeDAO;
use DPR\API\Domain\Models\Type;
use DI\Container;
use Tests\TestCase;
use PDOException;

class CreateTypeActionTest extends TestCase
{

    public function createTypeCases()
    {
        return array(
            # 1 active type
            array(
                array("typ_id" => 1, "typ_name" => "Pneumatic", "typ_status" => 1)
            ),
            # 1 active type
            array(
                array("typ_id" => 1, "typ_name" => "Infra-Red", "typ_status" => 1)
            )
        );
    }

    /** 
     * @test 
     * @dataProvider createTypeCases
     */
    public function createType($typeData)
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $type = new Type($typeData);

        $typeDAOProphecy = $this->prophesize(TypeDAO::class);
        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);

        $daoFactoryProphecy
           ->createTypeDAO()
           ->willReturn($typeDAOProphecy->reveal())
           ->shouldBeCalledOnce();

        $typeDAOProphecy
            ->createType()
            ->willReturn($type)
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(TypeDAO::class, $typeDAOProphecy->reveal());

        // Actual Results: Call the POST api/visitation/types endpoint
        $request = $this->createRequest('POST', 'api/visitation/types');
        
        $body = ['typ_name' => $type->getName()];
        $request = $request->withParsedBody($body);
        $response = $app->handle($request);

        $payload = (string) $response->getBody();

        // Expected Results: Retrieve correct original data
        $expectedPayload = new ActionPayload(200, ['type' => $type]);
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

        $typeDAOProphecy = $this->prophesize(TypeDAO::class);
        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);

        $daoFactoryProphecy
           ->createTypeDAO()
           ->willReturn($typeDAOProphecy->reveal())
           ->shouldBeCalledOnce();

        $typeDAOProphecy
            ->createType()
            ->willReturn(new PDOException("Connection failed: SQLSTATE[HY000] [2002] Connection refused"))
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(TypeDAO::class, $typeDAOProphecy->reveal());

        // Actual Results: Call the POST api/visitation/types endpoint
        $request = $this->createRequest('POST', 'api/visitation/types');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();
 
         // Expected Results: Retrieve correct original data
         $expectedPayload = new ActionPayload(500, ['message' => "Could not retrieve list of Types: Connection failed: SQLSTATE[HY000] [2002] Connection refused"]);
         $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}
