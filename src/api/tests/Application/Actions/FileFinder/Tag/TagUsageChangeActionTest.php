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

class TagUsageChangeActionTest extends TestCase
{

    public function changeUsageStatusCases()
    {
        $randomIds = range(1, 50);
        shuffle($randomIds);
        $randomIds = array_chunk($randomIds, 5)[rand(0, 4)];
        $titles = ["APC", "P-Card", "WEX", "Permits", "Annual Pass"];
        for ($i = 0; $i < 5; $i++) {
            $tag = new Tag(["tag_id" => $randomIds[$i], "tag_title" => $titles[$i], "tag_active" => rand(0, 1)]);
            $data[] = array(
                array("tagObject" => $tag)
            );
        }
        return $data;
    }

    /** 
     * @test 
     * @dataProvider changeUsageStatusCases
     */
    public function changeTagActiveStatus($testData)
    {
        $app = $this->getAppInstance();
        /** @var Container $container */
        $container = $app->getContainer();

        $tagObject = $testData["tagObject"];
        $tagId = (int) $tagObject->id;

        # Given a DB table for Tag and an id for a Tag
        $tagDAOProphecy = $this->prophesize(TagDAO::class);
        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);
        $daoFactoryProphecy
            ->createTagDAO()
            ->willReturn($tagDAOProphecy->reveal())
            ->shouldBeCalledOnce();

        $tagDAOProphecy
            ->switchActiveStatusForId($tagId)
            ->willReturn($tagObject)
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(TagDAO::class, $tagDAOProphecy->reveal());

        # When the PATCH api/filefinder/tags/{id}/active endpoint is reached with an id
        $request = $this->createRequest('PATCH', "/api/filefinder/tags/${tagId}/active");
        $response = $app->handle($request);

        # A message with the updated usage status of the Tag is returned
        $expectedMessage = "Switched Tag '$tagObject->title' usage status to " . ($tagObject->active ? "Active" : "Inactive");
        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(200, [
            "message" => $expectedMessage
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

        $randId = rand(0, 50);

        $tagDAOProphecy = $this->prophesize(TagDAO::class);
        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);
        $daoFactoryProphecy
            ->createTagDAO()
            ->willReturn($tagDAOProphecy->reveal())
            ->shouldBeCalledOnce();

        # Given that the Tag DAO throws a PDOException
        $tagDAOProphecy
            ->switchActiveStatusForId($randId)
            ->willThrow(new PDOException("Error 1836 SQLSTATE[HY000] Running in read-only mode"))
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(TagDAO::class, $tagDAOProphecy->reveal());

        # When the PATCH api/filefinder/tags/{id}/active endpoint is reached
        $request = $this->createRequest('PATCH', "/api/filefinder/tags/$randId/active");
        $response = $app->handle($request);

        # The server responds with status code 500, and an error message
        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(
            500,
            ['message' => "Failed to switch active status: Error 1836 SQLSTATE[HY000] Running in read-only mode"]
        );
        $serializedExpectedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);
        $this->assertEquals($serializedExpectedPayload, $payload);
    }
}
