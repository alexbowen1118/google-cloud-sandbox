<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions\Files\Categories;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;

class GetCategoriesAction extends Action
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $result = [
            'files' => $this->DAOFactory->ExampleDAO->getFiles()
        ];

        $this->logger->info("Users list was viewed.");

        return $this->respondWithData($result);
    }
}
