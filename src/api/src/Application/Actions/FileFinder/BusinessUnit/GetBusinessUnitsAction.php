<?php

declare(strict_types=1);

namespace DPR\API\Application\Actions\FileFinder\BusinessUnit;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;
use Exception;

class GetBusinessUnitsAction extends Action
{

    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $businessUnitDAO = $this->DAOFactory->createBusinessUnitDAO();
        try {
            $result = [
                'businessunits' => $businessUnitDAO->getBusinessUnits($this->request->getQueryParams())
            ];
            $this->logger->info("Business Unit list was retrieved.");
            return $this->respondWithData($result);
        } catch (Exception $e) {
            $message = "Could not retrieve list of Business Units: " . $e->getMessage();
            $this->logger->error($message);
            return $this->respondWithData(["message" => $message], 500);
        }
    }
}
