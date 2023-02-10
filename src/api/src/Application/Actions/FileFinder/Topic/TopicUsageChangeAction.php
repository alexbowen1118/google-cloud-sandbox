<?php

declare(strict_types=1);

namespace DPR\API\Application\Actions\FileFinder\Topic;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;
use DPR\API\Application\Actions\ActionPayload;
use Exception;

class TopicUsageChangeAction extends Action
{

    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $topicDAO = $this->DAOFactory->createTopicDAO();
        $top_id = $this->args["id"];
        try {
            $topic = $topicDAO->switchActiveStatusForId($top_id);
            $message = "Switched Topic '$topic->title' usage status to " . ($topic->active ? "Active" : "Inactive");
            $this->logger->info($message);
            return $this->respondWithData(["message" => $message]);
        } catch (Exception $e) {
            $message = "Failed to switch active status: " . $e->getMessage();
            $this->logger->error($message);
            return $this->respondWithData(["message" => $message], 500);
        }
    }
}
