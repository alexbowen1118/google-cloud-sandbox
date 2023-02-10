<?php
declare(strict_types=1);

namespace Tests\Application\Actions\Visitation\Brands;

use DPR\API\Application\Actions\ActionPayload;
use DPR\API\Infrastructure\Persistence\DAO\DAOFactory;
use DPR\API\Infrastructure\Persistence\DAO\BrandDAO;
use DPR\API\Domain\Models\Brand;
use DI\Container;
use Tests\TestCase;
use PDOException;

class DeleteBrandActionTest extends TestCase
{

    public function deleteBrandCases()
    {
        return array(
            # 1 active brand
            array(
                array("brn_id" => 1, "brn_name" => "Particle", "brn_status" => 1)
            ),
            # 1 active brand
            array(
                array("brn_id" => 1, "brn_name" => "Adafruit", "brn_status" => 1)
            )
        );
    }

    /** 
     * @test 
     * @dataProvider deleteBrandCases
     */
    public function deleteBrand($brandData)
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $brand = new Brand($brandData);
        $brand->setStatus(0);

        $brandDAOProphecy = $this->prophesize(BrandDAO::class);
        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);

        $daoFactoryProphecy
           ->createBrandDAO()
           ->willReturn($brandDAOProphecy->reveal())
           ->shouldBeCalledOnce();

        $brandDAOProphecy
            ->deleteBrand(1)
            ->willReturn($brand)
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(BrandDAO::class, $brandDAOProphecy->reveal());

        // Actual Results: Call the DELETE api/visitation/brands/1 endpoint
        $request = $this->createRequest('DELETE', 'api/visitation/brands/1');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();

        // Expected Results: Retrieve correct original data
        $expectedPayload = new ActionPayload(200, ['brand' => $brand]);
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

        $brandDAOProphecy = $this->prophesize(BrandDAO::class);
        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);

        $daoFactoryProphecy
           ->createBrandDAO()
           ->willReturn($brandDAOProphecy->reveal())
           ->shouldBeCalledOnce();

        $brandDAOProphecy
            ->deleteBrand(1)
            ->willReturn(new PDOException("Connection failed: SQLSTATE[HY000] [2002] Connection refused"))
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(BrandDAO::class, $brandDAOProphecy->reveal());

        // Actual Results: Call the DELETE api/visitation/brands/1 endpoint
        $request = $this->createRequest('DELETE', 'api/visitation/brands/1');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();

        // Expected Results: Retrieve correct original data
        $expectedPayload = new ActionPayload(500, ['message' => "Could not retrieve list of Brands: Connection failed: SQLSTATE[HY000] [2002] Connection refused"]);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}
