<?php

declare(strict_types=1);

namespace DPR\API\Application\Actions\FileFinder\BusinessUnit;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;
use Exception;

class BusinessUnitIncrementCountAction extends Action
{

    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $businessUnitId = $this->args["id"];
        $businessUnitDAO = $this->DAOFactory->createBusinessUnitDAO();
        try {
            $businessUnit = $businessUnitDAO->incrementCountForId($businessUnitId);
            $message = "Incremented usage count for Business Unit '$businessUnit->title' to '$businessUnit->count'";
            $this->logger->info($message);
            return $this->respondWithData(["message" => $message, "data" => $businessUnit]);
        } catch (Exception $e) {
            $message = "Failed to increment usage count: " . $e->getMessage();
            $this->logger->error($message);
            return $this->respondWithData(["message" => $message], 500);
        }
    }
}
