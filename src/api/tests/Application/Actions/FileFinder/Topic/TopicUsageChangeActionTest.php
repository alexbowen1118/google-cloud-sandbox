<?php

declare(strict_types=1);

namespace Tests\Application\Actions\FileFinder\Topic;

use DPR\API\Application\Actions\ActionPayload;
use DPR\API\Domain\Models\Topic;
use DI\Container;
use DPR\API\Infrastructure\Persistence\DAO\DAOFactory;
use DPR\API\Infrastructure\Persistence\DAO\FileFinder\TopicDAO;
use PDOException;
use Tests\TestCase;

class TopicUsageChangeActionTest extends TestCase
{

    public function changeUsageStatusCases()
    {
        $randomIds = range(1, 50);
        shuffle($randomIds);
        $randomIds = array_chunk($randomIds, 5)[rand(0, 4)];
        $topicTitles = ["Title One", "Title Two", "Title Three", "Title Four", "Title Five"];
        $topicDescriptions = ["Description One", "Description Two", "Description Three", "Description Four", "Description Five"];
        for ($i = 0; $i < 5; $i++) {
            $topic = new Topic(["top_id" => $randomIds[$i], "top_title" => $topicTitles[$i], "top_description" => $topicDescriptions[$i], "top_active" => rand(0, 1)]);
            $data[] = array(
                array("topicObject" => $topic)
            );
        }
        return $data;
    }

    /** 
     * @test 
     * @dataProvider changeUsageStatusCases
     */
    public function changeTopicActiveStatus($testData)
    {
        $app = $this->getAppInstance();
        /** @var Container $container */
        $container = $app->getContainer();

        $topicObject = $testData["topicObject"];
        $topicId = (int) $topicObject->id;

        # Given a DB table for Topic and an id for a Topic
        $topicDAOProphecy = $this->prophesize(TopicDAO::class);
        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);
        $daoFactoryProphecy
            ->createTopicDAO()
            ->willReturn($topicDAOProphecy->reveal())
            ->shouldBeCalledOnce();

        $topicDAOProphecy
            ->switchActiveStatusForId($topicId)
            ->willReturn($topicObject)
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(TopicDAO::class, $topicDAOProphecy->reveal());

        # When the PATCH api/filefinder/topics/{id}/active endpoint is reached with an id
        $request = $this->createRequest('PATCH', "/api/filefinder/topics/${topicId}/active");
        $response = $app->handle($request);

        # A message with the updated usage status of the Topic is returned
        $expectedMessage = "Switched Topic '$topicObject->title' usage status to " . ($topicObject->active ? "Active" : "Inactive");
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

        $topicDAOProphecy = $this->prophesize(TopicDAO::class);
        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);
        $daoFactoryProphecy
            ->createTopicDAO()
            ->willReturn($topicDAOProphecy->reveal())
            ->shouldBeCalledOnce();

        # Given that the Topic DAO throws a PDOException
        $topicDAOProphecy
            ->switchActiveStatusForId($randId)
            ->willThrow(new PDOException("Error 1836 SQLSTATE[HY000] Running in read-only mode"))
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(TopicDAO::class, $topicDAOProphecy->reveal());

        # When the PATCH api/filefinder/topics/{id}/active endpoint is reached
        $request = $this->createRequest('PATCH', "/api/filefinder/topics/$randId/active");
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
