<?php

declare(strict_types=1);

namespace DPR\API\Application\Actions\FileFinder\Search;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;
use DPR\API\Application\Actions\ActionPayload;
use Exception;

class SearchFilesAction extends Action
{

    protected function action(): Response
    {
        try {
            $searchDAO = $this->DAOFactory->createSearchDAO();
            $data = $this->request->getQueryParams();
            $output = $searchDAO->searchFiles($data);
            if (!empty($output["files"])) {
                $tagDAO = $this->DAOFactory->createTagDAO();
                $parkDAO = $this->DAOFactory->createParkDAO();
                foreach ($output["files"] as &$file) {
                    $file->setTags($tagDAO->getByFileId($file->getId()));
                    $file->setParks($parkDAO->getByFileId($file->getId()));
                }
            }
            $this->logger->info("Search was successfull");
            return $this->respond(new ActionPayload(200, $output, null));
        } catch (Exception $e) {
            $message = "Could not execute search: " . $e->getMessage();
            $this->logger->error($message);
            return $this->respondWithData(["message" => $message], 500);
        }
    }
}
