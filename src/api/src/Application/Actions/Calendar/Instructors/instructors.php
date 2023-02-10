<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use DPR\API\Infrastructure\Persistence\DB;

if (!function_exists('getInstructors')) {
    # Gets all instructors in the system
    function getInstructors(Request $request, Response $response)
    {
        try {
            $sql = "SELECT * FROM instructors";
            $db = new DB();
            $conn = $db->connect();

            $stmt = $conn->query($sql);
            $instructors = $stmt->fetchAll(PDO::FETCH_OBJ);
            $db = null;
            $response->getBody()->write(json_encode($instructors));
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

if (!function_exists('getInstructor')) {
    # Gets an instructor by their id in the instructors table
    function getInstructor(Request $request, Response $response, array $args)
    {
        try {
            $id = $args['id'];
            $sql = "SELECT * FROM instructors WHERE id = :id";
            $db = new DB();
            $conn = $db->connect();

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $instructor = $stmt->fetch(PDO::FETCH_OBJ);
            $db = null;
            $response->getBody()->write(json_encode($instructor));
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

if (!function_exists('getInstructorByUserID')) {
    # Gets an instructor by their user id
    function getInstructorByUserID(Request $request, Response $response, array $args)
    {
        try {
            $user_id = $args['user_id'];
            $sql = "SELECT * FROM instructors WHERE user_id = :user_id";
            $db = new DB();
            $conn = $db->connect();

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            $instructor = $stmt->fetch(PDO::FETCH_OBJ);
            $db = null;
            $response->getBody()->write(json_encode($instructor));
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

if (!function_exists('getInstructorSections')) {
    # Gets an instructor's sections by the instructor id
    function getInstructorSections(Request $request, Response $response, array $args)
    {
        try {

            $id = $args['id'];
            $sql = "SELECT * FROM sections WHERE sections.instructor_id = :id";
            $db = new DB();
            $conn = $db->connect();
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $instructor = $stmt->fetchAll(PDO::FETCH_OBJ);
            $db = null;
            $response->getBody()->write(json_encode($instructor));
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

if (!function_exists('addInstructor')) {
    # Adds a instructor using JSON
    function addInstructor(Request $request, Response $response)
    {
        try {
            $input = json_decode($request->getBody());
            $sql = "INSERT INTO instructors (title, first_name, last_name, addr1, addr2, city, state, zip,
         phone, fax, email, website, user_id) VALUE (:title, :first_name, :last_name, :addr1,
         :addr2, :city, :state, :zip, :phone, :fax, :email, :website, :user_id)";

            $db = new DB();
            $conn = $db->connect();
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':title', $input->title);
            $stmt->bindParam(':first_name', $input->first_name);
            $stmt->bindParam(':last_name', $input->last_name);
            $stmt->bindParam(':addr1', $input->addr1);
            $stmt->bindParam(':addr2', $input->addr2);
            $stmt->bindParam(':city', $input->city);
            $stmt->bindParam(':state', $input->state);
            $stmt->bindParam(':zip', $input->zip);
            $stmt->bindParam(':phone', $input->phone);
            $stmt->bindParam(':fax', $input->fax);
            $stmt->bindParam(':email', $input->email);
            $stmt->bindParam(':website', $input->website);
            $stmt->bindParam(':user_id', $input->user_id);
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

if (!function_exists('updateInstructor')) {
    # Updates an instructor
    function updateInstructor(Request $request, Response $response, array $args)
    {
        try {
            $id = $args['id'];
            $input = json_decode($request->getBody());

            $sql = "UPDATE instructors SET title = :title, first_name = :first_name, last_name = :last_name,
         addr1 = :addr1, addr2 = :addr2, city = :city, state = :state, zip  = :zip, phone = :phone,
         fax = :fax, email = :email, website = :website WHERE id = :id";
            $db = new DB();
            $conn = $db->connect();
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':title', $input->title);
            $stmt->bindParam(':first_name', $input->first_name);
            $stmt->bindParam(':last_name', $input->last_name);
            $stmt->bindParam(':addr1', $input->addr1);
            $stmt->bindParam(':addr2', $input->addr2);
            $stmt->bindParam(':city', $input->city);
            $stmt->bindParam(':state', $input->state);
            $stmt->bindParam(':zip', $input->zip);
            $stmt->bindParam(':phone', $input->phone);
            $stmt->bindParam(':fax', $input->fax);
            $stmt->bindParam(':email', $input->email);
            $stmt->bindParam(':website', $input->website);

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

if (!function_exists('deleteInstructor')) {
    # Deletes an instructor by id
    function deleteInstructor(Request $request, Response $response, array $args)
    {
        try {
            $id = $args['id'];
            $sql = "DELETE FROM instructors WHERE id=:id";
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

if (!function_exists('linkInstructorsToCourses')) {
    # Links the Instructors table to the Courses table by performing a join on an instructor's courses and the course names
    function linkInstructorsToCourses(Request $request, Response $response)
    {
        try {
            $sql = "SELECT * from instructors INNER JOIN courses on instructors.courses=courses.name";
            $db = new DB();
            $conn = $db->connect();

            $stmt = $conn->query($sql);
            $instructors = $stmt->fetchAll(PDO::FETCH_OBJ);
            $db = null;
            $response->getBody()->write(json_encode($instructors));
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

if (!function_exists('linkInstructorsToSections')) {
    # Links the Instructors table to the Sections table by performing a join on an instructors sections and the instructor field in Sections
    function linkInstructorsToSections(Request $request, Response $response)
    {
        try {
            $queryinstructorname = "SELECT concat(first_name, ' ', last_name) as name from instructors";
            $sql = "SELECT * from instructors INNER JOIN courses on instructors.name=sections.instructor";
            $db = new DB();
            $conn = $db->connect();

            $stmt = $conn->query($sql);
            $instructors = $stmt->fetchAll(PDO::FETCH_OBJ);
            $db = null;
            $response->getBody()->write(json_encode($instructors));
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
