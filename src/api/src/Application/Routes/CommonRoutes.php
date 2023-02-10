<?php

namespace DPR\API\Application\Routes;

use DPR\API\Application\Actions\Common\Authentication\GetAuthenticationTokenAction;
use DPR\API\Application\Actions\Common\Park\GetParksAction;
use DPR\API\Application\Middleware\TokenAuthMiddleware;
use Slim\Routing\RouteCollectorProxy;

class CommonRoutes
{
    function __invoke(RouteCollectorProxy $group)
    {
        $group->get('/parks', GetParksAction::class)->setArgument("permissions", "Base, Manager, Admin, Super-Admin");
    }
}
