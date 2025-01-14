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
require_once '../app/controllers/TeacherController.php';
require_once '../app/controllers/StudentController.php';
session_start();

$router = new Router();
Route::setRouter($router);



// Auth Routes
Route::get('/', [CourseController::class, 'showHome']);
Route::get('/home', [CourseController::class, 'showHome']);
Route::get('/login', [AuthController::class, 'showLogin']);
Route::get('/signup', [AuthController::class, 'showSignup']); 
Route::post('/login', [AuthController::class, 'loginChecker']);
Route::post('/signup', [AuthController::class, 'signupChecker']);
Route::get('/logout', [AuthController::class, 'logout']);


// Course Routes
Route::get('/course', [CourseController::class, 'getAllCourses']);
Route::get('/courses', [CourseController::class, 'getAllCourses']);
Route::get('/course/{id}', [CourseController::class, 'getCourseById']);

// Chapter Routes
Route::get('/chapter/create', [ChapterController::class, 'showCreateForm']);
Route::post('/chapter/create', [ChapterController::class, 'createChapter']);
Route::get('/chapter/add-content', [ChapterController::class, 'showAddContentForm']);
Route::post('/chapter/add-content', [ChapterController::class, 'addContent']);
Route::get('/chapter/add-content/course/:courseId/chapter/:chapterId', [ChapterController::class, 'showAddContentForm']);// Alternative format

// Teacher Routes
Route::get('/teacher/dashboard', [TeacherController::class, 'dashboard']);
// Teacher routes
Route::get('/teacher/dashboard', [TeacherController::class, 'dashboard']);
Route::get('/teacher/course/create', [TeacherController::class, 'createCourse']);
Route::post('/teacher/course/store', [TeacherController::class, 'storeCourse']);
Route::get('/teacher/course/:id/add-content', [TeacherController::class, 'addContent']);
Route::post('/teacher/course/content/store', [TeacherController::class, 'storeContent']);




// Dispatch la requête
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
