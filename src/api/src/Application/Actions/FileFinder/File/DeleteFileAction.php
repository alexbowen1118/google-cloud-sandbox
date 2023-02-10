<?php

declare(strict_types=1);

namespace DPR\API\Application\Actions\FileFinder\File;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;
use Exception;

class DeleteFileAction extends Action
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $fileDAO = $this->DAOFactory->createFileDAO();
        $fileId = $this->args["id"];
        try {
            $fileDAO->deleteFile($fileId);
            $message = "Deleted file with id " . $fileId;
            $this->logger->info("Files list was retrieved.");
            return $this->respondWithData(["message" => $message]);
        } catch (Exception $e) {
            $message = "Could not retrieve files: " . $e->getMessage();
            $this->logger->error($message);
            return $this->respondWithData(["message" => $message], 500);
        }
    }
}
