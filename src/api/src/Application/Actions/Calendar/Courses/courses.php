<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use DPR\API\Infrastructure\Persistence\DB;


if (!function_exists('getCourses')) {
    # Gets all courses in the system
    function getCourses(Request $request, Response $response)
    {
        try {
            $sql = "SELECT * FROM courses";
            $db = new DB();
            $conn = $db->connect();

            $stmt = $conn->query($sql);
            $courses = $stmt->fetchAll(PDO::FETCH_OBJ);
            $db = null;
            $response->getBody()->write(json_encode($courses));
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

if (!function_exists('getCourse')) {

    # Gets a course by id
    function getCourse(Request $request, Response $response, array $args)
    {
        try {
            $id = $args['id'];
            $sql = "SELECT * FROM courses WHERE id = :id";
            $db = new DB();
            $conn = $db->connect();

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $course = $stmt->fetch(PDO::FETCH_OBJ);

            $sql = "SELECT subject_id FROM course_subjects WHERE course_id = :id";
            $stmt2 = $conn->prepare($sql);
            $stmt2->bindParam(':id', $id);
            $stmt2->execute();
            $subjects = $stmt2->fetchAll(PDO::FETCH_OBJ);
            $course->subjects = $subjects;

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

if (!function_exists('addCourse')) {
    # Adds a course using JSON
    function addCourse(Request $request, Response $response)
    {
        try {
            $input = json_decode($request->getBody());
            $sql = "INSERT INTO courses (name, description, requirements) VALUE (:name, :description, :requirements)";

            $db = new DB();
            $conn = $db->connect();

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $input->name);
            $stmt->bindParam(':description', $input->description);
            $stmt->bindParam(':requirements', $input->requirements);

            $result = $stmt->execute();

            $stmt = $conn->prepare("SELECT id FROM courses WHERE name=?");
            $stmt->execute([$input->name]);
            $courseId = $stmt->fetchColumn();

            $subjects = json_decode($input->subjects);
            $currentSubjectId = 1;
            foreach ($subjects as $subject) {
                if ($subject) {
                    $sql = "INSERT INTO course_subjects (course_id, subject_id) VALUE (:course_id, :subject_id)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':course_id', $courseId);
                    $stmt->bindParam(':subject_id', $currentSubjectId);
                    $stmt->execute();
                }
                $currentSubjectId++;
            }

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

if (!function_exists('updateCourse')) {
    # Updates a course
    function updateCourse(Request $request, Response $response, array $args)
    {
        try {
            $id = $args['id'];
            $input = json_decode($request->getBody());
            $sql = "UPDATE courses SET name = :name, description = :description, requirements = :requirements
         WHERE id = :id";
            $db = new DB();
            $conn = $db->connect();
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':name', $input->name);
            $stmt->bindParam(':description', $input->description);
            $stmt->bindParam(':requirements', $input->requirements);

            $result = $stmt->execute();

            $sql = "SELECT subject_id FROM course_subjects WHERE course_id=:id";
            $stmt2 = $conn->prepare($sql);
            $stmt2->bindParam(':id', $id);
            $stmt2->execute();

            $oldSubjects = $stmt2->fetchAll(PDO::FETCH_COLUMN, 0);
            $currentSubjectId = 1;
            $newSubjects = json_decode($input->subjects);
            foreach ($newSubjects as $subject) {
                $subjectIsInDatabase = in_array($currentSubjectId, $oldSubjects);
                //if the subject is in the new list and not in the old list, add it
                if ($subject && !$subjectIsInDatabase) {
                    $sql = "INSERT INTO course_subjects (course_id, subject_id) VALUE (:course_id, :subject_id)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':course_id', $id);
                    $stmt->bindParam(':subject_id', $currentSubjectId);
                    $stmt->execute();
                    //if the subject is not in the new list and is in the database, remove it
                } elseif (!$subject && $subjectIsInDatabase) {
                    $sql = "DELETE FROM course_subjects WHERE course_id=:course_id AND subject_id=:subject_id";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':course_id', $id);
                    $stmt->bindParam(':subject_id', $currentSubjectId);
                    $stmt->execute();
                }
                $currentSubjectId++;
            }
            //change to $result
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

if (!function_exists('deleteCourse')) {
    # Deletes a course by id
    function deleteCourse(Request $request, Response $response, array $args)
    {
        try {
            $id = $args['id'];
            $sql = "DELETE FROM courses WHERE id=:id";
            $db = new DB();
            $conn = $db->connect();

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);

            $result = $stmt->execute();

            $sql = "DELETE FROM course_subjects WHERE course_id=:id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $result2 = $stmt->execute();

            $db = null;
            $response->getBody()->write(json_encode(array($result, $result2)));
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
