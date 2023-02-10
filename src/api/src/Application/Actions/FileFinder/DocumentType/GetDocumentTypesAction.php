<?php

declare(strict_types=1);

namespace DPR\API\Application\Actions\FileFinder\DocumentType;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;
use Exception;

class GetDocumentTypesAction extends Action
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $documentTypeDAO = $this->DAOFactory->createDocumentTypeDAO();
        try {
            $result = [
                'documenttypes' => $documentTypeDAO->getDocumentTypes($this->request->getQueryParams())
            ];
            $this->logger->info("Document Type list was retrieved.");
            return $this->respondWithData($result);
        } catch (Exception $e) {
            $message = "Could not retrieve list of Document Types: " . $e->getMessage();
            $this->logger->error($message);
            return $this->respondWithData(["message" => $message], 500);
        }
    }
}
