<?php

declare(strict_types=1);

namespace DPR\API\Application\Actions\FileFinder\DocumentType;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;
use DPR\API\Application\Actions\ActionPayload;
use Exception;

class DocumentTypeUsageChangeAction extends Action
{

    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $documentTypeDAO = $this->DAOFactory->createDocumentTypeDAO();
        $dot_id = $this->args["id"];
        try {
            $documentType = $documentTypeDAO->switchActiveStatusForId($dot_id);
            $message = "Switched Document Type '$documentType->title' usage status to " . ($documentType->active ? "Active" : "Inactive");
            $this->logger->info($message);
            return $this->respondWithData(["message" => $message]);
        } catch (Exception $e) {
            $message = "Failed to switch active status: " . $e->getMessage();
            $this->logger->error($message);
            return $this->respondWithData(["message" => $message], 500);
        }
    }
}
