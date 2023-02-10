<?php

declare(strict_types=1);

namespace DPR\API\Application\Actions\FileFinder\Topic;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;
use Exception;

class EditTopicAction extends Action
{

    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $topicDAO = $this->DAOFactory->createTopicDAO();
        try {
            $id = $this->args["id"];
            $title = $this->getFormData()["title"] ?? "";
            $description = $this->getFormData()["description"] ?? "";
            $output = [];

            if (empty($title) && empty($description)) {
                $message = "Nothing to edit";
                $statusCode = 204;
            } else {
                $output["topic"] = $topicDAO->editTopicTitleAndDescription($id, $title, $description);
                $message = "Edited topic " . $id;
                $output["message"] = $message;
                $statusCode = 200;
            }

            $this->logger->info($message);
            return $this->respondWithData($output, $statusCode);
        } catch (Exception $e) {
            $message = "Failed to edit topic: " . $e->getMessage();
            $this->logger->error($message);
            return $this->respondWithData(["message" => $message], 500);
        }
    }
}
