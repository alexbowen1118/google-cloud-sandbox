<?php

declare(strict_types=1);

namespace DPR\API\Application\Actions\FileFinder\DeleteRequest;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;
use Exception;

class CreateDeleteRequestAction extends Action
{

    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $data = $this->getFormData();
        $fileDAO = $this->DAOFactory->createFileDAO();
        $fileAWSS3ObjectName = $fileDAO->getAWSS3ObjectName($data["file_id"]);
        $deleteRequestDAO = $this->DAOFactory->createDeleteRequestDAO();
        try {
            $newDeleteRequestId = $deleteRequestDAO->createDeleteRequest($data);
            $newDeleteRequest = $deleteRequestDAO->getById($newDeleteRequestId);
            $message = "Created delete request for file $fileAWSS3ObjectName";
            $this->logger->info($message);
            return $this->respondWithData(["message" => $message, "data" => $newDeleteRequest]);
        } catch (Exception $e) {
            $message = "Failed to create delete request: " . $e->getMessage();
            $this->logger->error($message);
            return $this->respondWithData(["message" => $message], 500);
        }
    }
}
