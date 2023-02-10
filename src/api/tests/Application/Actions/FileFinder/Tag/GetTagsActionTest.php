<?php

declare(strict_types=1);

namespace Tests\Application\Actions\FileFinder\Tag;

use DPR\API\Application\Actions\ActionPayload;
use DPR\API\Domain\Models\Tag;
use DI\Container;
use DPR\API\Infrastructure\Persistence\DAO\DAOFactory;
use DPR\API\Infrastructure\Persistence\DAO\FileFinder\TagDAO;
use PDOException;
use Prophecy\Argument;
use Tests\TestCase;

class GetTagsActionTest extends TestCase
{

    public function getAllTagsCases()
    {
        return array(
            # 1 active Tag
            array(
                array("tag_id" => 1, "tag_title" => "APC", "tag_active" => 1),
            ),
            # 1 inactive Tag
            array(
                array("tag_id" => 3, "tag_title" => "WEX", "tag_active" => 0),
            ),
            # 5 Tags active + nonactive
            array(
                array("tag_id" => 1, "tag_title" => "Permits", "tag_active" => 1),
                array("tag_id" => 3, "tag_title" => "Fire", "tag_active" => 0),
                array("tag_id" => 7, "tag_title" => "P-Card", "tag_active" => 1),
                array("tag_id" => 9, "tag_title" => "Annual Pass", "tag_active" => 1),
                array("tag_id" => 11, "tag_title" => "Travel Authorization/Travel Request", "tag_active" => 0)
            ),
            # No Tags
            array(array())
        );
    }

    /** 
     * @test 
     * @dataProvider getAllTagsCases
     */
    public function getsAllTags($tagData)
    {
        $app = $this->getAppInstance();
        /** @var Container $container */
        $container = $app->getContainer();

        $tags = [];
        foreach ($tagData as $data) {
            $tags[] = new Tag($data);
        }

        # Given a list of Tags in the DB
        $tagDAOProphecy = $this->prophesize(TagDAO::class);
        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);
        $daoFactoryProphecy
            ->createTagDAO()
            ->willReturn($tagDAOProphecy->reveal())
            ->shouldBeCalledOnce();

        $tagDAOProphecy
            ->getTags(Argument::any())
            ->willReturn($tags)
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(TagDAO::class, $tagDAOProphecy->reveal());

        # When the GET api/filefinder/tags endpoint is reached
        $request = $this->createRequest('GET', '/api/filefinder/tags');
        $response = $app->handle($request);

        # All the Tags from the DB are retrieved
        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(200, ['tags' => $tags]);
        $serializedExpectedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedExpectedPayload, $payload);
    }

    /** @test */
    public function willReturnServerError()
    {
        $app = $this->getAppInstance();
        /** @var Container $container */
        $container = $app->getContainer();

        $tagDAOProphecy = $this->prophesize(TagDAO::class);
        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);
        $daoFactoryProphecy
            ->createTagDAO()
            ->willReturn($tagDAOProphecy->reveal())
            ->shouldBeCalledOnce();

        # Given that the TagDAO throws a PDOException
        $tagDAOProphecy
            ->getTags(Argument::any())
            ->willThrow(new PDOException("Connection failed: SQLSTATE[HY000] [2002] Connection refused"))
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(TagDAO::class, $tagDAOProphecy->reveal());

        # When the GET api/filefinder/tags endpoint is reached
        $request = $this->createRequest('GET', '/api/filefinder/tags');
        $response = $app->handle($request);

        # The server responds with status code 500, and an error message
        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(500, ['message' => "Could not retrieve list of Tags: Connection failed: SQLSTATE[HY000] [2002] Connection refused"]);
        $serializedExpectedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedExpectedPayload, $payload);
    }
}
