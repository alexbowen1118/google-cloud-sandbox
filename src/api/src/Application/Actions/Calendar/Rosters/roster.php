<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use DPR\API\Infrastructure\Persistence\DB;

if (!function_exists('getRoster')) {
    # Returns all the Users in the Roster
    function getRoster(Request $request, Response $response, array $args)
    {
        try {
            $section_id = $args['section_id'];
            $sql = "SELECT roster.id, roster.section_id, roster.user_id, users.first_name, users.last_name
            FROM roster INNER JOIN users on roster.user_id=users.id
            where roster.section_id=:section_id";
            $db = new DB();
            $conn = $db->connect();

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':section_id', $section_id);
            $stmt->execute();
            $roster = $stmt->fetchAll(PDO::FETCH_OBJ);
            $db = null;
            $response->getBody()->write(json_encode($roster));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(200);
        } catch (PDOException $e) {
            $error = array(
                "message" => $e->getMessage()
            );

            $response->getBody()->write(json_encode($error));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(500);
        }
    };
}

if (!function_exists('addUserToRoster')) {
    # Adds a roster using JSON
    function addUserToRoster(Request $request, Response $response)
    {

        $input = json_decode($request->getBody());
        $sql = "INSERT INTO roster (section_id, user_id) VALUE (:section_id, :user_id)";

        try {
            $db = new DB();
            $conn = $db->connect();

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':section_id', $input->section_id);
            $stmt->bindParam(':user_id', $input->user_id);

            var_dump($input);


            $result = $stmt->execute();

            $db = null;
            $response->getBody()->write(json_encode($result));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(201);
        } catch (PDOException $e) {
            $error = array(
                "message" => $e->getMessage()
            );

            $response->getBody()->write(json_encode($error));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(500);
        }
    };
}

if (!function_exists('removeUserFromRoster')) {
    # Removes a user in the roster by their user_id, which is referred to as student_id in this function
    function removeUserFromRoster(Request $request, Response $response, array $args)
    {
        $id = $args['student_id'];
        $sql = "DELETE FROM roster WHERE id=:id";

        try {
            $db = new DB();
            $conn = $db->connect();

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);

            $result = $stmt->execute();

            $db = null;
            $response->getBody()->write(json_encode($result));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(200);
        } catch (PDOException $e) {
            $error = array(
                "message" => $e->getMessage()
            );

            $response->getBody()->write(json_encode($error));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(500);
        }
    }
}
