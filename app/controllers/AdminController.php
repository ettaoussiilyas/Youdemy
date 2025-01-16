<?php

require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Chapter.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Tag.php';

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
}

