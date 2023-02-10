<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use DPR\API\Infrastructure\Persistence\DB;


if (!function_exists('getSections')) {
    # Gets all sections in the system
    # Routed from all courses, so returns all rather than course specific sections
    function getSections(Request $request, Response $response)
    {
        $sql = "SELECT * FROM sections";

        try {
            $db = new DB();
            $conn = $db->connect();

            $stmt = $conn->query($sql);
            $sections = $stmt->fetchAll(PDO::FETCH_OBJ);
            $db = null;
            $response->getBody()->write(json_encode($sections));
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

if (!function_exists('getCourseSections')) {
    # Gets all sections of the specific Course
    # Routed from {course_id}, so only gets that course's sections
    function getCourseSections(Request $request, Response $response, array $args)
    {
        $course_id = $args['id'];
        $sql = "SELECT * FROM sections where course_id=:course_id";

        try {
            $db = new DB();
            $conn = $db->connect();
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':course_id', $course_id);
            $stmt->execute();
            $sections = $stmt->fetchAll(PDO::FETCH_OBJ);
            $db = null;
            $response->getBody()->write(json_encode($sections));
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

if (!function_exists('getSection')) {
    # Gets a section by its section_id
    function getSection(Request $request, Response $response, array $args)
    {
        $section_id = $args['section_id'];
        $sql = "SELECT * FROM sections WHERE section_id = $section_id";

        try {
            $db = new DB();
            $conn = $db->connect();

            $stmt = $conn->query($sql);
            $section = $stmt->fetch(PDO::FETCH_OBJ);
            $db = null;
            $response->getBody()->write(json_encode($section));
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

if (!function_exists('addSection')) {
    # Adds a section to the routed {course_id} using JSON
    function addSection(Request $request, Response $response)
    {

        $input = json_decode($request->getBody());
        $sql = "INSERT INTO sections (course_id, instructor_id, location, start_date, end_date, meeting_days, start_time, end_time, details, course_name)
        VALUE (:course_id, :instructor_id, :location, :start_date, :end_date, :meeting_days, :start_time, :end_time, :details, :course_name)";

        try {
            $db = new DB();
            $conn = $db->connect();

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':course_id', $input->course_id);
            $stmt->bindParam(':instructor_id', $input->instructor_id);
            $stmt->bindParam(':location', $input->location);
            $stmt->bindParam(':start_date', $input->start_date);
            $stmt->bindParam(':end_date', $input->end_date);
            $stmt->bindParam(':meeting_days', $input->meeting_days);
            $stmt->bindParam(':start_time', $input->start_time);
            $stmt->bindParam(':end_time', $input->end_time);
            $stmt->bindParam(':details', $input->details);
            $stmt->bindParam(':course_name', $input->course_name);

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

if (!function_exists('updateSection')) {
    # Updates the section {section_id} of the routed {course_id}
    function updateSection(Request $request, Response $response, array $args)
    {
        try {
            $id = $args['sectionID'];
            $input = json_decode($request->getBody());
            $sql = "UPDATE sections SET instructor_id = :instructor_id, course_id = :course_id, start_date = :start_date, end_date = :end_date, meeting_days = :meeting_days,
            start_time = :start_time, end_time = :end_time, details = :details, location = :location, course_name = :course_name
            WHERE id = :id";

            $db = new DB();
            $conn = $db->connect();
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':instructor_id', $input->instructor_id);
            $stmt->bindParam(':course_id', $input->course_id);
            $stmt->bindParam(':location', $input->location);
            $stmt->bindParam(':start_date', $input->start_date);
            $stmt->bindParam(':end_date', $input->end_date);
            $stmt->bindParam(':meeting_days', $input->meeting_days);
            $stmt->bindParam(':start_time', $input->start_time);
            $stmt->bindParam(':end_time', $input->end_time);
            $stmt->bindParam(':details', $input->details);
            $stmt->bindParam(':course_name', $input->course_name);

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

if (!function_exists('deleteSection')) {
    # Deletes a section by its {section_id}
    function deleteSection(Request $request, Response $response, array $args)
    {
        $id = $args['id'];
        $sql = "DELETE FROM sections WHERE id=:id";

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
