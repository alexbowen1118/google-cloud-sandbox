<?php

declare(strict_types=1);

namespace DPR\API\Application\Actions\FileFinder\DocumentType;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;
use Exception;

class CreateDocumentTypeAction extends Action
{

    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $newDocumentTypeTitle = $this->getFormData()["title"];
        $documentTypeDAO = $this->DAOFactory->createDocumentTypeDAO();
        try {
            $newDocumentTypeId = $documentTypeDAO->addNewDocumentType($newDocumentTypeTitle);
            $newDocumentType = $documentTypeDAO->getById($newDocumentTypeId);
            $message = "Created new Document Type '$newDocumentTypeTitle'";
            $this->logger->info($message);
            return $this->respondWithData(["message" => $message, "data" => $newDocumentType]);
        } catch (Exception $e) {
            $message = "Failed to create new Document Type: " . $e->getMessage();
            $this->logger->error($message);
            return $this->respondWithData(["message" => $message], 500);
        }
    }
}
