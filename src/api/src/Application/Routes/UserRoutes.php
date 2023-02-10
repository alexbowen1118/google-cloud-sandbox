<?php
namespace DPR\API\Application\Routes;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;

require(__DIR__.'/../Actions/Users/users.php');

class UserRoutes {

  function __invoke(RouteCollectorProxy $group) {

    // Return the logged-in user's information
    $group->get('/me', function (Request $request, Response $response, array $args) {
        return getMe($request, $response, $args);
    })->setArgument("permissions", "ALL");

    // Gets all users
    $group->get('', function (Request $request, Response $response) {
        return getUsers($request, $response);
    })->setArgument("permissions", "ALL");

    // Gets all users who are a specified role
    $group->get('/role/{role}', function (Request $request, Response $response, array $args) {
        return getUsersInRole($request, $response, $args);
    })->setArgument("permissions", "ALL");

    // Gets a user
    $group->get('/{id}', function (Request $request, Response $response, array $args) {
        return getUser($request, $response, $args);
    })->setArgument("permissions", "ALL");

    // Gets a user by username
    $group->get('/user/{username}', function (Request $request, Response $response, array $args) {
        return getUserByName($request, $response, $args);
    })->setArgument("permissions", "ALL");

    // Adds a user
    $group->post('', function (Request $request, Response $response) {
        return addUser($request, $response);
    })->setArgument("permissions", "ADMIN");

    // Updates a user
    $group->put('/{id}', function (Request $request, Response $response, array $args) {
        return updateUser($request, $response, $args);
    })->setArgument("permissions", "ADMIN");

    // Deletes a user
    $group->delete('/{id}', function (Request $request, Response $response, array $args) {
        return deleteUser($request, $response, $args);
    })->setArgument("permissions", "ADMIN");

  }
}