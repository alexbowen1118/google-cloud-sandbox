<?php

declare(strict_types=1);

namespace Tests\Application\Actions\FileFinder\Tag;

use DPR\API\Application\Actions\ActionPayload;
use DPR\API\Domain\Models\Tag;
use DI\Container;
use DPR\API\Infrastructure\Persistence\DAO\DAOFactory;
use DPR\API\Infrastructure\Persistence\DAO\FileFinder\TagDAO;
use PDOException;
use Tests\TestCase;

class CreateTagActionTest extends TestCase
{

    public function createTagCases()
    {
        $randomIds = range(1, 50);
        shuffle($randomIds);
        $randomIds = array_chunk($randomIds, 5)[rand(0, 4)];
        $titles = ["APC", "P-Card", "WEX", "Permits", "Annual Pass"];

        $data = array();
        for ($i = 0; $i < 5; $i++) {
            $tag = new Tag(["tag_id" => $randomIds[$i], "tag_title" => $titles[$i], "tag_active" => 1]);
            $data[] = array(
                array(
                    "newTitle" => $titles[$i], "returnId" => $randomIds[$i],
                    "expectedObject" => $tag
                )
            );
        }
        return $data;
    }

    /** 
     * @test 
     * @dataProvider createTagCases
     */
    public function createTag($testData)
    {
        $app = $this->getAppInstance();
        /** @var Container $container */
        $container = $app->getContainer();

        # Given a DB table for Tag and a new Tag name
        $tagDAOProphecy = $this->prophesize(TagDAO::class);
        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);
        $daoFactoryProphecy
            ->createTagDAO()
            ->willReturn($tagDAOProphecy->reveal())
            ->shouldBeCalledOnce();

        $tagDAOProphecy
            ->addNewTag($testData["newTitle"])
            ->willReturn($testData["returnId"])
            ->shouldBeCalledOnce();

        $tagDAOProphecy
            ->getById($testData["returnId"])
            ->willReturn($testData["expectedObject"])
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(TagDAO::class, $tagDAOProphecy->reveal());

        # When the POST api/filefinder/tags endpoint is reached with the new name
        $requestHeaders = [
            'HTTP_ACCEPT' => 'application/json',
        ];
        $request = $this->createRequest('POST', '/api/filefinder/tags', $requestHeaders);
        $request = $request->withParsedBody(array("title" => $testData["newTitle"]));
        $response = $app->handle($request);

        # The newly created Tag is returned
        $newTitle = $testData["newTitle"];
        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(200, [
            "message" => "Created new Tag '$newTitle'",
            "data" => $testData["expectedObject"]
        ]);
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

        # Given that the Tag DAO throws a PDOException
        $tagDAOProphecy
            ->addNewTag("New Tag")
            ->willThrow(new PDOException("Error 1169 SQLSTATE[23000]: Can't write, because of unique constraint, to table 'tag'"))
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(TagDAO::class, $tagDAOProphecy->reveal());

        # When the POST api/filefinder/tags endpoint is reached
        $request = $this->createRequest('POST', '/api/filefinder/tags');
        $request = $request->withParsedBody(["title" => "New Tag"]);
        $response = $app->handle($request);

        # The server responds with status code 500, and an error message
        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(
            500,
            ['message' => "Failed to create new Tag: Error 1169 SQLSTATE[23000]: Can't write, because of unique constraint, to table 'tag'"]
        );
        $serializedExpectedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);
        $this->assertEquals($serializedExpectedPayload, $payload);
    }
}
