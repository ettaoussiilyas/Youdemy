<?php

require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Chapter.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Tag.php';
require_once __DIR__ . '/../models/User.php';

class AdminController extends BaseController{

    private $courseModel;
    private $chapterModel;
    private $categoryModel;
    private $tagModel;
    private $studentModel;
    private $teacherModel;
    private $enrollmentModel;
    
    public function __construct() {

        parent::__construct();
        $this->courseModel = new Course();
        $this->chapterModel = new Chapter();
        $this->categoryModel = new Category();
        $this->tagModel = new Tag();
        $this->studentModel = new Student();
        $this->teacherModel = new Teacher();
        $this->enrollmentModel = new Enrollment();
   
    }

    public function dashboard(){

        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: /login');
            exit;
        }

        try {
            $lastCourses = $this->courseModel->getLastCourses();

            // Statistiques globales
            $totalStudents = $this->studentModel->getTotalStudents();
            $totalTeachers = $this->teacherModel->getTotalTeachers();
            $totalCourses = $this->courseModel->getTotalCourses();
            

            // Enseignants en attente
            $pendingTeachers = $this->teacherModel->getPendingTeachers();


            $this->render('admin/dashboard', [
                'totalStudents' => $totalStudents,
                'totalTeachers' => $totalTeachers,
                'totalCourses' => $totalCourses,
                'pendingTeachers' => $pendingTeachers,
                'lastCourses' => $lastCourses,
                'errors' => null
            ]);

        } catch (Exception $e) {
            $this->render('admin/dashboard', [
                'totalStudents' => 0,
                'totalTeachers' => 0,
                'totalCourses' => 0,
                'pendingTeachers' => [],
                'lastCourses' => [],
                'errors' => $e->getMessage()
            ]);
        }
    }

    public function users() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: /login');
            exit;
        }

        try {
            $userModel = new User();
            $users = $userModel->getAllUsers();

            $this->render('admin/users', [
                'users' => $users
            ]);
        } catch (Exception $e) {
            $this->render('admin/users', [
                'users' => [],
                'errors' => $e->getMessage()
            ]);
        }
    }

    public function deleteUser($userId) {
        error_log("deleteUser called with userId=$userId");
        
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            error_log("Access denied: not admin");
            header('Location: /login');
            exit;
        }

        try {
            $userModel = new User();
            $result = $userModel->deleteUser($userId);
            error_log("Delete user result: " . ($result ? "success" : "failed"));
            header('Location: /admin/users');
        } catch (Exception $e) {
            error_log("Error deleting user: " . $e->getMessage());
            header('Location: /admin/users?error=delete_failed');
        }
    }

    public function updateUserStatus($userId, $newStatus) {
        error_log("updateUserStatus called with userId=$userId, newStatus=$newStatus");
        
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            error_log("Access denied: not admin");
            header('Location: /login');
            exit;
        }

        try {
            $userModel = new User();
            $result = $userModel->updateUserStatus($userId, $newStatus);
            error_log("Update status result: " . ($result ? "success" : "failed"));
            header('Location: /admin/users');
        } catch (Exception $e) {
            error_log("Error updating user status: " . $e->getMessage());
            header('Location: /admin/users?error=status_update_failed');
        }
    }

    public function content() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: /login');
            exit;
        }

        try {
            $courseModel = new Course();
            $courses = $courseModel->getAllCourses();

            $this->render('admin/content', [
                'courses' => $courses
            ]);
        } catch (Exception $e) {
            error_log("Error loading courses: " . $e->getMessage());
            $this->render('admin/content', [
                'courses' => [],
                'error' => 'Failed to load courses'
            ]);
        }
    }

    public function deleteCourse($courseId) {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: /login');
            exit;
        }

        try {
            $courseModel = new Course();
            $courseModel->deleteCourse($courseId);
            header('Location: /admin/content');
        } catch (Exception $e) {
            error_log("Error deleting course: " . $e->getMessage());
            header('Location: /admin/content?error=delete_failed');
        }
    }

    public function categories() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: /login');
            exit;
        }

        try {
            $categories = $this->categoryModel->getAllCategories();
            $tags = $this->tagModel->getAllTags();

            $this->render('admin/categories', [
                'categories' => $categories,
                'tags' => $tags
            ]);
        } catch (Exception $e) {
            $this->render('admin/categories', [
                'categories' => [],
                'tags' => [],
                'error' => $e->getMessage()
            ]);
        }
    }

    public function addCategory() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
            try {
                $name = $_POST['name'];
                $description = $_POST['description'] ?? '';
                $this->categoryModel->addCategory($name, $description);
                header('Location: /admin/categories');
            } catch (Exception $e) {
                header('Location: /admin/categories?error=add_failed');
            }
        }
    }

    public function deleteCategory($categoryId) {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: /login');
            exit;
        }

        try {
            $this->categoryModel->deleteCategory($categoryId);
            header('Location: /admin/categories');
        } catch (Exception $e) {
            header('Location: /admin/categories?error=delete_failed');
        }
    }

    public function addTag() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
            try {
                $this->tagModel->addTag($_POST['name']);
                header('Location: /admin/categories');
            } catch (Exception $e) {
                header('Location: /admin/categories?error=add_failed');
            }
        }
    }

    public function deleteTag($tagId) {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: /login');
            exit;
        }

        try {
            $this->tagModel->deleteTag($tagId);
            header('Location: /admin/categories');
        } catch (Exception $e) {
            header('Location: /admin/categories?error=delete_failed');
        }
    }
}

