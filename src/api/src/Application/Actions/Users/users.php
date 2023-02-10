<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use DPR\API\Infrastructure\Persistence\DB;


if (!function_exists('getUsers')) {
    # Gets all users in the system
    function getUsers(Request $request, Response $response)
    {
        try {
            $sql = "SELECT * FROM users";
            $db = new DB();
            $conn = $db->connect();

            $stmt = $conn->query($sql);
            $users = $stmt->fetchAll(PDO::FETCH_OBJ);
            $db = null;
            $response->getBody()->write(json_encode($users));
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

if (!function_exists('getUsersInRole')) {
    # Gets all users in the system of a specified roles
    function getUsersInRole(Request $request, Response $response, array $args)
    {
        try {
            $role = strtoupper($args['role']);
            $sql = "SELECT * FROM users WHERE role = :role";
            $db = new DB();
            $conn = $db->connect();

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':role', $role);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_OBJ);
            $db = null;
            $response->getBody()->write(json_encode($users));
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

if (!function_exists('getUser')) {
    # Gets a user by id
    function getUser(Request $request, Response $response, array $args)
    {
        try {
            $id = $args['id'];
            $sql = "SELECT * FROM users WHERE id = :id";
            $db = new DB();
            $conn = $db->connect();

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $course = $stmt->fetch(PDO::FETCH_OBJ);
            $db = null;
            $response->getBody()->write(json_encode($course));
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

if (!function_exists('getUserByName')) {
    # Gets a user by name
    function getUserByName(Request $request, Response $response, array $args)
    {
        try {
            $username = $args['username'];
            $sql = "SELECT id FROM users WHERE user = :username";
            $db = new DB();
            $conn = $db->connect();
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $course = $stmt->fetch(PDO::FETCH_OBJ);
            $db = null;
            $response->getBody()->write(json_encode($course));
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

if (!function_exists('addUser')) {
    # Adds a user using JSON
    function addUser(Request $request, Response $response)
    {
        try {
            $input = json_decode($request->getBody());
            $sql = "INSERT INTO users (user, hash, first_name, last_name, role) VALUE (:user, :hash, :first_name, :last_name, :role)";
            $db = new DB();
            $conn = $db->connect();

            $stmt = $conn->prepare($sql);
            # default user name is first_name
            $stmt->bindParam(':user', $input->first_name);
            # default password is password
            $stmt->bindParam(':hash', $input->hash);
            $stmt->bindParam(':first_name', $input->first_name);
            $stmt->bindParam(':last_name', $input->last_name);
            $stmt->bindParam(':role', $input->role);

            $result = $stmt->execute();
            $returnId = (string)($conn->lastInsertId());

            $db = null;
            $response->getBody()->write(json_encode($returnId));
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

if (!function_exists('updateUser')) {
    # Updates a user
    function updateUser(Request $request, Response $response, array $args)
    {
        try {
            $id = $args['id'];
            $input = json_decode($request->getBody());

            $sql = "UPDATE users SET first_name = :first_name, last_name = :last_name,
         role = :role WHERE id = :id";
            $db = new DB();
            $conn = $db->connect();
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':first_name', $input->first_name);
            $stmt->bindParam(':last_name', $input->last_name);
            $stmt->bindParam(':role', $input->role);

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
    }
}

if (!function_exists('deleteUser')) {
    # Deletes a course by id
    function deleteUser(Request $request, Response $response, array $args)
    {
        try {
            $id = $args['id'];
            $sql = "DELETE FROM users WHERE id=:id";
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
    };
}

if (!function_exists('getMe')) {
    function getMe(Request $request, Response $response, array $args)
    {
        $response->getBody()->write($request->getAttribute('claims'));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    };
}
