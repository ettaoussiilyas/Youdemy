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
require_once '../app/controllers/AdminController.php';
require_once '../app/controllers/HomeController.php';

session_start();

$router = new Router();
Route::setRouter($router);



// Auth Routes
Route::get('/', [HomeController::class, 'index']);
Route::get('/home', [HomeController::class, 'index']);
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
Route::get('/teacher/course/create', [TeacherController::class, 'createCourse']);
Route::post('/teacher/course/store', [TeacherController::class, 'storeCourse']);
Route::post('/teacher/course/content/store', [TeacherController::class, 'storeContent']);
Route::get('/teacher/mycourses', [TeacherController::class, 'myCourses']);
Route::get('/teacher/course/delete', [TeacherController::class, 'deleteCourse']);
Route::get('/teacher/course/edit', [TeacherController::class, 'editCourse']);
Route::post('/teacher/course/update', [TeacherController::class, 'updateCourse']);
// Route::post('/teacher/chapter/delete/{id}', [TeacherController::class, 'deleteChapter']);
Route::post('/teacher/chapter/delete', [TeacherController::class, 'deleteChapter']);
Route::get('/teacher/stats', [TeacherController::class, 'showStats']);

// Student Routes
Route::get('/student/dashboard', [StudentController::class, 'dashboard']);
Route::get('/student/courses', [StudentController::class, 'myCourses']);
Route::get('/student/browse', [StudentController::class, 'browseCourses']);
Route::get('/student/course/{id}', [StudentController::class, 'viewCourse']);
Route::get('/student/profile', [StudentController::class, 'profile']);
Route::get('/student/course/details/{id}', [StudentController::class, 'courseDetails']);
Route::get('/student/course/enroll/{id}', [StudentController::class, 'enrollCourse']);

// Admin Routes
Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
Route::get('/admin/users', [AdminController::class, 'users']);
Route::get('/admin/users/updateStatus/{id}/{status}', [AdminController::class, 'updateUserStatus']);
Route::get('/admin/users/delete/{id}', [AdminController::class, 'deleteUser']);

// Content Management Routes
Route::get('/admin/content', [AdminController::class, 'content']);
Route::get('/admin/content/delete/{id}', [AdminController::class, 'deleteCourse']);

// Categories & Tags Routes
Route::get('/admin/categories', [AdminController::class, 'categories']);
Route::post('/admin/categories/add', [AdminController::class, 'addCategory']);
Route::get('/admin/categories/delete/{id}', [AdminController::class, 'deleteCategory']);
Route::post('/admin/tags/add', [AdminController::class, 'addTag']);
Route::get('/admin/tags/delete/{id}', [AdminController::class, 'deleteTag']);
Route::get('/admin/statistics', [AdminController::class, 'stats']);

//search api
Route::get('/api/courses/filter', [CourseController::class, 'filterCourses']);
//Route to browse courses
Route::get('/courses', [CourseController::class, 'browseCourses']);
// Dans la section des routes
// Dispatch la requête
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

// Route::get('/api/courses/filter', [CourseController::class, 'filter']);

