<?php

declare(strict_types=1);

namespace DPR\API\Application\Actions\FileFinder\Upload;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;
use DPR\API\Application\Actions\ActionPayload;
use Exception;
use Ramsey\Uuid\Uuid;

class UploadFilesAction extends Action
{

    /**
     * Creates a unique object name for an AWS S3 file upload
     * @param string filename is the name of the file being uploaded
     * @return string returns the filename concatenated to a UUIDv4 string
     */
    private function createObjectName(string $filename)
    {
        return Uuid::uuid4()->toString() . '-' . $filename;
    }

    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $data = $this->getFormData();
        ### request body validation
        try {
            if (!is_string($data['metadata'])) {
                throw new Exception("Request body format is incorrect!");
            }
            $metadata = get_object_vars(json_decode($data['metadata']));
            if (!array_key_exists("files", $metadata)) {
                throw new Exception("Request body format is incorrect: File metadata cannot be read!");
            }
            ### converts stdClass to arrays
            $completeMetadata = array("topic" => get_object_vars($metadata["topic"]), "uploader_id" => $metadata["uploader_id"], "files" => array());
            if(key_exists("replace", $metadata)) {
                $completeMetadata["replace"] = $metadata["replace"];
            }
            foreach ($metadata["files"] as $fileStdClass) {
                $fileMetadataArray = get_object_vars($fileStdClass);
                if (!$fileMetadataArray) {
                    throw new Exception("Request body format is incorrect: File metadata cannot be read!");
                }
                $completeMetadata["files"][$fileMetadataArray["filename"]] = $fileMetadataArray;
            }
        } catch (Exception $e) {
            $this->logger->error("Failed to parse request body: " . $e->getMessage());
            return $this->respond(new ActionPayload(400, ["message" => $e->getMessage()]));
        }
        try {
            ### retrieves files sent to the backend, and uploads them to the backend
            ### for each file, adds corresponding s3 object name to metadata array
            $files = $this->request->getUploadedFiles();
            $uploadedToS3 = [];
            foreach ($files as $file) {
                $clientFilename = $file->getClientFilename();
                $fileObjectName = $this->createObjectName($clientFilename);
                $fileContents = $file->getStream()->__toString();
                $this->awsAPI->putObject($fileObjectName, $fileContents);
                $completeMetadata["files"][$clientFilename]['aws_s3_object_name'] = $fileObjectName;
                $uploadedToS3[] = $fileObjectName;
            }
            ### persist metadata in db
            $uploadDAO = $this->DAOFactory->createUploadDAO();
            $uploadDAO->uploadFiles($completeMetadata);
            $this->logger->info("Successful file upload!");
            return $this->respond(new ActionPayload());
        } catch (Exception $e) {
            if (!empty($uploadedToS3)) {
                $this->awsAPI->deleteObjects($uploadedToS3);
            }
            $this->logger->error("Failure: " . $e->getMessage());
            return $this->respond(new ActionPayload(500, ["message" => $e->getMessage()]));
        }
    }
}
