<?php

declare(strict_types=1);

namespace DPR\API\Application\Actions\FileFinder\Tag;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;
use Exception;

class CreateTagAction extends Action
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $newTagTitle = $this->getFormData()["title"];
        $tagDAO = $this->DAOFactory->createTagDAO();
        try {
            $newTagId = $tagDAO->addNewTag($newTagTitle);
            $newTag = $tagDAO->getById($newTagId);
            $message = "Created new Tag '$newTagTitle'";
            $this->logger->info($message);
            return $this->respondWithData(["message" => $message, "data" => $newTag]);
        } catch (Exception $e) {
            $message = "Failed to create new Tag: " . $e->getMessage();
            $this->logger->error($message);
            return $this->respondWithData(["message" => $message], 500);
        }
    }
}
