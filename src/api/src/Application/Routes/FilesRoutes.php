<?php
namespace DPR\API\Application\Routes;

use DPR\API\Application\Actions\Files\Categories\GetCategoriesAction;
use Slim\Routing\RouteCollectorProxy;


class FilesRoutes {

  function __invoke(RouteCollectorProxy $group) {

    $group->get('/categories', GetCategoriesAction::class);

  }
}