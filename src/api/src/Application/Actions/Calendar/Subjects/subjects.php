<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use DPR\API\Infrastructure\Persistence\DB;


if (!function_exists('getSubjects')) {
    # Gets all subjects in the system
    function getSubjects(Request $request, Response $response)
    {
        try {
            $sql = "SELECT * FROM subjects";
            $db = new DB();
            $conn = $db->connect();

            $stmt = $conn->query($sql);
            $subjects = $stmt->fetchAll(PDO::FETCH_OBJ);
            $db = null;
            $response->getBody()->write(json_encode($subjects));
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

if (!function_exists('getSubject')) {
    # Gets a subject by id
    function getSubject(Request $request, Response $response, array $args)
    {
        try {
            $id = $args['id'];
            $sql = "SELECT * FROM subjects WHERE id = :id";
            $db = new DB();
            $conn = $db->connect();

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $subject = $stmt->fetch(PDO::FETCH_OBJ);
            $db = null;
            $response->getBody()->write(json_encode($subject));
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


if (!function_exists('addSubject')) {
    # Adds a subject using JSON
    function addSubject(Request $request, Response $response)
    {
        try {
            $input = json_decode($request->getBody());
            $sql = "INSERT INTO subject (name) VALUE (:name)";

            $db = new DB();
            $conn = $db->connect();

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $input->name);

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

if (!function_exists('updateSubject')) {
    # Updates a subject
    function updateSubject(Request $request, Response $response, array $args)
    {
        try {
            $id = $args['id'];
            $input = json_decode($request->getBody());
            $sql = "UPDATE subject SET name = :name
         WHERE id = :id";
            $db = new DB();
            $conn = $db->connect();
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':name', $input->name);

            $result = $stmt->execute();
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
    };
}
