<?php

declare(strict_types=1);

namespace DPR\API\Application\Actions\FileFinder\File;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;
use Exception;

class GetFilePresignedUrlAction extends Action
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        try {
            $objectName = $this->args['objectName'];
            $result = [
                'filePresignedUrl' => $this->awsAPI->getPresignedUrl($objectName)
            ];
            $this->logger->info("Presigned url for file object ${objectName} was generated.");
            return $this->respondWithData($result);
        } catch (Exception $e) {
            $message = "Could not generate presigned url for file object: ${objectName}" . $e->getMessage();
            $this->logger->error($message);
            return $this->respondWithData(["message" => $message], 500);
        }
    }
}
