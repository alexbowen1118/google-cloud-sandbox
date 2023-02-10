<?php

declare(strict_types=1);

namespace DPR\API\Application\Actions\FileFinder\Tag;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;
use Exception;

class GetTagsAction extends Action
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $tagDAO = $this->DAOFactory->createTagDAO();
        try {
            $result = [
                'tags' => $tagDAO->getTags($this->request->getQueryParams())
            ];
            $this->logger->info("Tag list was retrieved.");
            return $this->respondWithData($result);
        } catch (Exception $e) {
            $message = "Could not retrieve list of Tags: " . $e->getMessage();
            $this->logger->error($message);
            return $this->respondWithData(["message" => $message], 500);
        }
    }
}
