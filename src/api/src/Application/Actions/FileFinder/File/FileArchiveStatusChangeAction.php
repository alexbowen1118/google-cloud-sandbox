<?php

declare(strict_types=1);

namespace DPR\API\Application\Actions\FileFinder\File;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;
use Exception;

class FileArchiveStatusChangeAction extends Action
{
    /**
     * {@inheritdoc}
     * 
     */
    protected function action(): Response
    {
        $fileId = $this->args["id"];
        $fileDAO = $this->DAOFactory->createFileDAO();
        try {
            $result = [
                'files' => $fileDAO->switchArchiveStatusForId($fileId)
            ];
            $this->logger->info("File archive status was changed for file: " . $fileId);
            return $this->respondWithData($result);
        } catch (Exception $e) {
            $message = "Could not change archive status for file: " . $e->getMessage();
            $this->logger->error($message);
            return $this->respondWithData(["message" => $message], 500);
        }
    }
}
