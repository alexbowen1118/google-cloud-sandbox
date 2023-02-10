<?php
namespace DPR\API\Application\Routes;

use Slim\Routing\RouteCollectorProxy;
use DPR\API\Application\Middleware\TokenAuthMiddleware;

class Routes {

  function __invoke(RouteCollectorProxy $group) {

    $group->group('/auth', AuthRoutes::class);
    $group->group('/users', UserRoutes::class)->add(TokenAuthMiddleware::class);
    $group->group('/calendar', CalendarRoutes::class)->add(TokenAuthMiddleware::class);
    $group->group('/filefinder', FileFinderRoutes::class);
    $group->group('/files', FilesRoutes::class)->add(TokenAuthMiddleware::class);
    $group->group('/visitation', VisitationRoutes::class);
    $group->group('/parks', ParkRoutes::class)->add(TokenAuthMiddleware::class);

  }
}
