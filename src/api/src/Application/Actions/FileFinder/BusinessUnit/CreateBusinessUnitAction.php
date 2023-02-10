<?php

declare(strict_types=1);

namespace DPR\API\Application\Actions\FileFinder\BusinessUnit;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;
use Exception;

class CreateBusinessUnitAction extends Action
{

    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $newBusinessUnitTitle = $this->getFormData()["title"];
        $businessUnitDAO = $this->DAOFactory->createBusinessUnitDAO();
        try {
            $newBusinessUnitId = $businessUnitDAO->addNewBusinessUnit($newBusinessUnitTitle);
            $newBusinessUnit = $businessUnitDAO->getById($newBusinessUnitId);
            $message = "Created new Business Unit '$newBusinessUnitTitle'";
            $this->logger->info($message);
            return $this->respondWithData(["message" => $message, "data" => $newBusinessUnit]);
        } catch (Exception $e) {
            $message = "Failed to create new Business Unit: " . $e->getMessage();
            $this->logger->error($message);
            return $this->respondWithData(["message" => $message], 500);
        }
    }
}
