<?php

declare(strict_types=1);

namespace DPR\API\Application\Actions\FileFinder\BusinessUnit;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;
use Exception;

class BusinessUnitUsageChangeAction extends Action
{

    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $businessUnitDAO = $this->DAOFactory->createBusinessUnitDAO();
        $bun_id = $this->args["id"];
        try {
            $businessUnit = $businessUnitDAO->switchActiveStatusForId($bun_id);
            $message = "Switched Business Unit '$businessUnit->title' usage status to " . ($businessUnit->active ? "Active" : "Inactive");
            $this->logger->info($message);
            return $this->respondWithData(["message" => $message]);
        } catch (Exception $e) {
            $message = "Failed to switch active status: " . $e->getMessage();
            $this->logger->error($message);
            return $this->respondWithData(["message" => $message], 500);
        }
    }
}
