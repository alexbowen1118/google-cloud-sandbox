<?php
namespace DPR\API\Application\Routes;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;

use DPR\API\Application\Middleware\TokenAuthMiddleware;



require(__DIR__.'/../Actions/Calendar/Courses/courses.php');
require(__DIR__.'/../Actions/Calendar/Instructors/instructors.php');
require(__DIR__.'/../Actions/Calendar/Rosters/roster.php');
require(__DIR__.'/../Actions/Calendar/Sections/sections.php');
require(__DIR__.'/../Actions/Calendar/Subjects/subjects.php');

class CalendarRoutes {

  function __invoke(RouteCollectorProxy $group) {

    // Course group
    $group->group('/courses', function ($group) {
      // Gets all courses
      $group->get('', function (Request $request, Response $response) {
          return getCourses($request, $response);
      })->setArgument("permissions", "ALL");

      // Gets a course
      $group->get('/{id}', function (Request $request, Response $response, array $args) {
          return getCourse($request, $response, $args);
      })->setArgument("permissions", "ALL");

      // Adds a course
      $group->post('', function (Request $request, Response $response) {
          return addCourse($request, $response);
      })->setArgument("permissions", "ADMIN");

      // Updates a course
      $group->put('/{id}', function (Request $request, Response $response, array $args) {
          return updateCourse($request, $response, $args);
      })->setArgument("permissions", "ADMIN");

      // Deletes a course
      $group->delete('/{id}', function (Request $request, Response $response, array $args) {
          return deleteCourse($request, $response, $args);
      })->setArgument("permissions", "ADMIN");
    })->add(TokenAuthMiddleware::class);

    // Subjects group
    $group->group('/subjects', function ($group) {

      // Gets all Subjects
      $group->get('', function (Request $request, Response $response) {
          return getSubjects($request, $response);
      })->setArgument("permissions", "ALL");

      // Gets an Subject
      $group->get('/{id}', function (Request $request, Response $response, array $args) {
          return getSubject($request, $response, $args);
      })->setArgument("permissions", "ALL");

      // Adds an Subject
      $group->post('', function (Request $request, Response $response) {
          return addSubject($request, $response);
      })->setArgument("permissions", "ADMIN");

      // Updates an Subject
      $group->put('/{id}', function (Request $request, Response $response, array $args) {
          return updateSubject($request, $response, $args);
      })->setArgument("permissions", "ADMIN");
    })->add(TokenAuthMiddleware::class);

    // Instructor group
    $group->group('/instructors', function ($group) {

      // Gets all instructors
      $group->get('', function (Request $request, Response $response) {
          return getInstructors($request, $response);
      })->setArgument("permissions", "ALL");

      // Gets an instructor
      $group->get('/{id}', function (Request $request, Response $response, array $args) {
          return getInstructor($request, $response, $args);
      })->setArgument("permissions", "ALL");

      // Gets an instructor by its user id
      $group->get('/user_id/{user_id}', function (Request $request, Response $response, array $args) {
          return getInstructorByUserID($request, $response, $args);
      })->setArgument("permissions", "ADMIN, INSTRUCTOR");

      // Gets an instructor's sections that they teach
      $group->get('/{id}/sections', function (Request $request, Response $response, array $args) {
          return getInstructorSections($request, $response, $args);
      })->setArgument("permissions", "ALL");

      // Adds an instructor
      $group->post('', function (Request $request, Response $response) {
          return addInstructor($request, $response);
      })->setArgument("permissions", "ADMIN");

      // Updates an instrucor
      $group->put('/{id}', function (Request $request, Response $response, array $args) {
          return updateInstructor($request, $response, $args);
      })->setArgument("permissions", "ADMIN, INSTRUCTOR");

      // Deletes an instructor
      $group->delete('/{id}', function (Request $request, Response $response, array $args) {
          return deleteInstructor($request, $response, $args);
      })->setArgument("permissions", "ADMIN");
    })->add(TokenAuthMiddleware::class);

    // Group for all sections
    $group->group('/sections', function ($group) {

      // Gets all sections
      $group->get('', function (Request $request, Response $response) {
          return getSections($request, $response);
      })->setArgument("permissions", "ALL");
    })->add(TokenAuthMiddleware::class);

    // Section group which is attached to one specific Course
    $group->group('/courses/{id}/sections', function ($group) {

      // Gets all sections
      $group->get('', function (Request $request, Response $response, array $args) {
          return getCourseSections($request, $response, $args);
      })->setArgument("permissions", "ALL");

      // Gets a section
      $group->get('/{sectionID}', function (Request $request, Response $response, array $args) {
          return getSection($request, $response, $args);
      })->setArgument("permissions", "ALL");

      // Adds a section
      $group->post('', function (Request $request, Response $response) {
          return addSection($request, $response);
      })->setArgument("permissions", "ADMIN, INSTRUCTOR");

      // Updates a section
      $group->put('/{sectionID}', function (Request $request, Response $response, array $args) {
          return updateSection($request, $response, $args);
      })->setArgument("permissions", "ADMIN, INSTRUCTOR");

      // Deletes a section
      $group->delete('/{sectionID}', function (Request $request, Response $response, array $args) {
          return deleteSection($request, $response, $args);
      })->setArgument("permissions", "ADMIN, INSTRUCTOR");
    })->add(TokenAuthMiddleware::class);

    // Rection group which is attached to one specific section
    $group->group('/courses/{id}/sections/{section_id}/roster', function ($group) {

      // Gets the whole roster of the section tied to the request
      $group->get('', function (Request $request, Response $response, array $args) {
          return getRoster($request, $response, $args);
      })->setArgument("permissions", "ADMIN, INSTRUCTOR");;

      // Adds a user to the roster
      $group->post('', function (Request $request, Response $response) {
          return addUserToRoster($request, $response);
      })->setArgument("permissions", "ALL");;

      // Removes a User from the roster
      $group->delete('/{student_id}', function (Request $request, Response $response, array $args) {
          return removeUserFromRoster($request, $response, $args);
      })->setArgument("permissions", "ALL");;
    })->add(TokenAuthMiddleware::class);







  }
}



