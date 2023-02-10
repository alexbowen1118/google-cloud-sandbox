<?php

declare(strict_types=1);

namespace DPR\API\Application\Actions\FileFinder\Topic;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;
use Exception;

class ViewTopicAction extends Action
{

    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $topicDAO = $this->DAOFactory->createTopicDAO();
        $fileDAO = $this->DAOFactory->createFileDAO();
        $top_id = $this->args["id"];
        try {
            $output = [
                "topic" => $topicDAO->getById($top_id),
                "files" => [
                    "unarchived" => $fileDAO->getUnarchivedFilesByTopicId($top_id),
                    "archived" => $fileDAO->getArchivedFilesByTopicId($top_id)
                ]
            ];

            if (!empty($output["files"])) {
                $tagDAO = $this->DAOFactory->createTagDAO();
                $parkDAO = $this->DAOFactory->createParkDAO();
                foreach ($output["files"] as &$archivedOrUnarchivedFiles) {
                    foreach ($archivedOrUnarchivedFiles as &$file) {
                        $file->setTags($tagDAO->getByFileId($file->getId()));
                        $file->setParks($parkDAO->getByFileId($file->getId()));
                    }
                }
            }

            $message = "Retrieved topic and files for id " . $top_id;
            $this->logger->info($message);
            return $this->respondWithData($output, 200);
        } catch (Exception $e) {
            $message = "Failed to switch active status: " . $e->getMessage();
            $this->logger->error($message);
            return $this->respondWithData(["message" => $message], 500);
        }
    }
}
