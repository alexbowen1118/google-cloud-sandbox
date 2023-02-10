<?php
namespace DPR\API\Application\Routes;

use DPR\API\Application\Actions;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;

require(__DIR__.'/../Actions/Auth/auth.php');

class AuthRoutes {

  function __invoke(RouteCollectorProxy $group) {
    // Calendar application login, returns JWT response
    $group->post('/token', function (Request $request, Response $response) {
        return getToken($request, $response);
    });

    // Route for new visitation application login, returns JWT response
    $group->post('/login', Actions\Auth\GetAuthenticationTokenAction::class);
  }
}
