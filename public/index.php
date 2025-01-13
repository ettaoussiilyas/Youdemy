<?php

// First: Database
require_once '../app/config/db.php';

// Second: Core classes
require_once '../core/BaseController.php';  // Updated correct path
require_once '../core/Router.php';
require_once '../core/Route.php';

// Third: Controllers
require_once '../app/controllers/AuthController.php';
require_once '../app/controllers/CourseController.php';
require_once '../app/controllers/ChapterController.php';

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

// Chapter Routes
Route::get('/chapter/create', [ChapterController::class, 'showCreateForm']);
Route::post('/chapter/create', [ChapterController::class, 'createChapter']);
Route::get('/chapter/add-content', [ChapterController::class, 'showAddContentForm']);
Route::post('/chapter/add-content', [ChapterController::class, 'addContent']);

// Alternative format
Route::get('/chapter/add-content/course/:courseId/chapter/:chapterId', [ChapterController::class, 'showAddContentForm']);



// Dispatch la requête
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
