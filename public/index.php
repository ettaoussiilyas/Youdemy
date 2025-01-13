<?php


require_once '../app/config/db.php';
require_once('../core/BaseController.php');
require_once '../core/Router.php';
require_once '../core/Route.php';
require_once '../app/controllers/AuthController.php';
require_once '../app/controllers/CourseController.php';


session_start();

$router = new Router();
Route::setRouter($router);

// Auth Routes
Route::get('/', [AuthController::class, 'showHome']);
Route::get('/home', [AuthController::class, 'showHome']);

// Course Routes
Route::get('/course', [CourseController::class, 'getAllCourses']);
Route::get('/courses', [CourseController::class, 'getAllCourses']);
Route::get('/course/{id}', [CourseController::class, 'getCourseById']);




$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
