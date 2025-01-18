<?php

require_once __DIR__ . '/../../core/RoleMiddleware.php';
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Chapter.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Tag.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/notifications/AccountStatusNotification.php';

class AdminController extends BaseController{

    private $courseModel;
    private $chapterModel;
    private $categoryModel;
    private $tagModel;
    private $studentModel;
    private $teacherModel;
    private $enrollmentModel;
    private $userModel;
    
    public function __construct() {

        parent::__construct();
        RoleMiddleware::checkRole(['admin']);
        $this->courseModel = new Course();
        $this->chapterModel = new Chapter();
        $this->categoryModel = new Category();
        $this->tagModel = new Tag();
        $this->studentModel = new Student();
        $this->teacherModel = new Teacher();
        $this->enrollmentModel = new Enrollment();
        $this->userModel = new User();
   
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

    public function updateUserStatus($id, $status) {
        try {
            // Récupérer les informations de l'utilisateur
            $user = $this->userModel->getUserById($id);
            
            if (!$user) {
                $_SESSION['error'] = "Utilisateur non trouvé";
                header('Location: /admin/users');
                exit;
            }

            // Mettre à jour le statut
            $updated = $this->userModel->updateUserStatus($id, $status);
            
            if ($updated) {
                // Envoyer la notification
                try {
                    $notification = new AccountStatusNotification(
                        $id,      // ID de l'utilisateur qui reçoit la notification
                        $status,  // nouveau statut ('active' ou 'blocked')
                        $user['name']  // nom de l'utilisateur
                    );
                    $notification->send();
                } catch (Exception $e) {
                    error_log("Erreur lors de l'envoi de la notification: " . $e->getMessage());
                }

                $_SESSION['success'] = "Statut de l'utilisateur mis à jour avec succès";
            } else {
                $_SESSION['error'] = "Échec de la mise à jour du statut";
            }
            
        } catch (Exception $e) {
            $_SESSION['error'] = "Une erreur est survenue";
            error_log($e->getMessage());
        }
        
        header('Location: /admin/users');
        exit;
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
           
            $tagNames = explode(',', $_POST['name']); // Split by comma
            
            $success = true;
            $addedCount = 0;
            
            foreach ($tagNames as $tagName) {
                $tagName = trim($tagName); // Remove whitespace
                if (!empty($tagName)) {
                    if ($this->tagModel->addTag($tagName)) {
                        $addedCount++;
                    } else {
                        $success = false;
                    }
                }
            }

            if ($success && $addedCount > 0) {
                $_SESSION['success'] = "Successfully added " . $addedCount . " tag(s)";
            } else {
                $_SESSION['error'] = "Error adding one or more tags";
            }
            
            header('Location: /admin/categories');
            exit;
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

    public function stats() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: /login');
            exit;
        }

        try {
            // Get basic stats
            $totalUsers = $this->userModel->getTotalUsers();
            $activeCourses = $this->courseModel->getTotalActiveCourses();
            $totalCategories = $this->categoryModel->getTotalCategories();
            $totalTags = $this->tagModel->getTotalTags();

            // Get growth rates
            $userGrowth = $this->userModel->getGrowthRate();
            $courseGrowth = $this->courseModel->getGrowthRate();

            // Get popular categories and tags
            $popularCategories = $this->categoryModel->getPopularCategories();
            $popularTags = $this->tagModel->getPopularTags();

            $this->render('admin/stats', [
                'totalUsers' => $totalUsers,
                'activeCourses' => $activeCourses,
                'totalCategories' => $totalCategories,
                'totalTags' => $totalTags,
                'userGrowth' => $userGrowth,
                'courseGrowth' => $courseGrowth,
                'popularCategories' => $popularCategories,
                'popularTags' => $popularTags
            ]);
        } catch (Exception $e) {
            error_log("Error loading stats: " . $e->getMessage());
            $this->render('admin/stats', [
                'error' => 'Failed to load statistics'
            ]);
        }
    }
}

