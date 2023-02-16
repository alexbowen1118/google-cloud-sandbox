<?php
namespace DPR\API\Application\Routes;

use DPR\API\Application\Actions;
use Slim\Routing\RouteCollectorProxy;

class ParkRoutes
{

    function __invoke(RouteCollectorProxy $group)
    {
        // Gets all parks
        $group->get('', Actions\Parks\GetParksAction::class)
            ->setArgument("permissions", "ALL");

        // Gets all park codes
        $group->get('/codes', Actions\Parks\GetParkCodesAction::class)
            ->setArgument("permissions", "ALL");

        // Gets a park by ID
        $group->get('/{par_id}', Actions\Parks\GetParkAction::class)
            ->setArgument("permissions", "ALL");

        // Gets a park by code
        $group->get('/code{par_code}', Actions\Parks\GetParkByCodeAction::class)
            ->setArgument("permissions", "ALL");
    }
}