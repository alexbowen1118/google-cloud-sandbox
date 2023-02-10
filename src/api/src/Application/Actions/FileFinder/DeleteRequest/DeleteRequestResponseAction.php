<?php

declare(strict_types=1);

namespace DPR\API\Application\Actions\FileFinder\DeleteRequest;

use Psr\Http\Message\ResponseInterface as Response;
use DPR\API\Application\Actions\Action;
use Exception;

class DeleteRequestResponseAction extends Action
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $response = filter_var($this->getFormData()["response"], FILTER_VALIDATE_BOOL);
        $dlr_id = $this->args["id"];
        $deleteRequestDAO = $this->DAOFactory->createDeleteRequestDAO();
        $fileDAO = $this->DAOFactory->createFileDAO();
        $DeleteRequest = $deleteRequestDAO->getById($dlr_id);
        $fileAWSS3ObjectName = $fileDAO->getAWSS3ObjectName($DeleteRequest->fileId);
        if ($response) /* accepting delete request */ {
            try {
                $awsResult = $this->awsAPI->deleteObject($fileAWSS3ObjectName);
                ### deleting file entry and associated attributes
                $deleteRequestDAO->acceptDeleteRequest($DeleteRequest->fileId);
                $this->logger->info("Successful file delete: " . $fileAWSS3ObjectName);
                return $this->respondWithData(["message" => "Successful File Delete"]);
            } catch (Exception $e) {
                if ($awsResult->get("DeleteMarker")) {
                    $this->awsAPI->deleteObjectVersion($fileAWSS3ObjectName, $awsResult->get("VersionId"));
                }
                $this->logger->error("Failed to delete file ($fileAWSS3ObjectName): " . $e->getMessage());
                return $this->respondWithData(["message" => $e->getMessage()], 500);
            }
        } else /* denying delete request */ {
            try {
                $deleteRequestDAO->denyDeleteRequest($dlr_id);
                $this->logger->info("Denied delete request: " . $fileAWSS3ObjectName);
                return $this->respondWithData(["message" => "Denied delete request for file: $fileAWSS3ObjectName"]);
            } catch (Exception $e) {
                $this->logger->error("Error occurred when denying delete request: " . $e->getMessage());
                return $this->respondWithData(["message" => $e->getMessage()], 500);
            }
        }
    }
}
