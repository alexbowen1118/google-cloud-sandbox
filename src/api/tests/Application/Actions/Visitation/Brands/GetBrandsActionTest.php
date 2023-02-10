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

class GetBrandsActionTest extends TestCase
{

    public function getAllBrandsCases()
    {
        return array(
            # 1 active brand
            array(
                array("brn_id" => 1, "brn_name" => "Particle", "brn_status" => 1)
            ),
            # 1 inactive brand
            array(
                array("brn_id" => 1, "brn_name" => "Particle", "brn_status" => 0)
            ),
            # 5 active + nonactive brands
            array(
                array("brn_id" => 1, "brn_name" => "Particle", "brn_status" => 1),
                array("brn_id" => 2, "brn_name" => "Adafruit", "brn_status" => 0),
                array("brn_id" => 3, "brn_name" => "RedBear", "brn_status" => 1),
                array("brn_id" => 4, "brn_name" => "Microchip", "brn_status" => 1),
                array("brn_id" => 5, "brn_name" => "SODAQ", "brn_status" => 0)
            ),
            # No brands
            array(array())
        );
    }

    /** 
     * @test 
     * @dataProvider getAllBrandsCases
     */
    public function getAllBrands($brandData)
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $brands = [];
        foreach ($brandData as $data) {
            $brands[] = new Brand($data);
        }

        $brandDAOProphecy = $this->prophesize(BrandDAO::class);
        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);
        
        $daoFactoryProphecy
            ->createBrandDAO()
            ->willReturn($brandDAOProphecy->reveal())
            ->shouldBeCalledOnce();
        
        $brandDAOProphecy
            ->getAllBrands()
            ->willReturn($brands)
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(BrandDAO::class, $brandDAOProphecy->reveal());

        // Actual Results: Call the GET api/visitation/brands endpoint
        $request = $this->createRequest('GET', 'api/visitation/brands');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();

        // Expected Results: Retrieve correct original data
        $expectedPayload = new ActionPayload(200, ['brands' => $brands]);
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
            ->getAllBrands()
            ->willReturn(new PDOException("Connection failed: SQLSTATE[HY000] [2002] Connection refused"))
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(BrandDAO::class, $brandDAOProphecy->reveal());

        // Actual Results: Call the GET api/visitation/brands endpoint
        $request = $this->createRequest('GET', 'api/visitation/brands');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();

        // Expected Results: Retrieve correct original data
        $expectedPayload = new ActionPayload(500, ['message' => "Could not retrieve list of Brands: Connection failed: SQLSTATE[HY000] [2002] Connection refused"]);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}
