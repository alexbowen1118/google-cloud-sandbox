<?php

declare(strict_types=1);

namespace DPR\API\Application\Actions\FileFinder\Tag;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;
use DPR\API\Application\Actions\ActionPayload;
use Exception;

class TagUsageChangeAction extends Action
{

    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $tagDAO = $this->DAOFactory->createTagDAO();
        $tag_id = (int) $this->args["id"];
        try {
            $tag = $tagDAO->switchActiveStatusForId($tag_id);
            $message = "Switched Tag '$tag->title' usage status to " . ($tag->active ? "Active" : "Inactive");
            $this->logger->info($message);
            return $this->respondWithData(["message" => $message]);
        } catch (Exception $e) {
            $message = "Failed to switch active status: " . $e->getMessage();
            $this->logger->error($message);
            return $this->respondWithData(["message" => $message], 500);
        }
    }
}
