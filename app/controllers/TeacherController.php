<?php
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Chapter.php';
require_once __DIR__ . '/../models/Category.php';

class TeacherController extends BaseController {
    private $courseModel;
    private $chapterModel;
    private $categoryModel;
    
    public function __construct() {
        parent::__construct();
        $this->courseModel = new Course();
        $this->chapterModel = new Chapter();
        $this->categoryModel = new Category();
    }
    
    public function dashboard() {
        // Get teacher ID from session
        $teacherId = $_SESSION['user_id'];
        
        // Get statistics
        $totalCourses = $this->courseModel->getTeacherCoursesCount($teacherId);
        $totalStudents = $this->courseModel->getTeacherTotalStudents($teacherId);
        $activeCourses = $this->courseModel->getTeacherActiveCoursesCount($teacherId);
        $recentCourses = $this->courseModel->getTeacherRecentCourses($teacherId);
        
        $this->renderTeacher('dashboard', [
            'totalCourses' => $totalCourses,
            'totalStudents' => $totalStudents,
            'activeCourses' => $activeCourses,
            'recentCourses' => $recentCourses
        ]);
    }

    public function createCourse() {
        // Get categories for the dropdown
        $categories = $this->categoryModel->getAll();
        
        $this->render('teacher/course/create', [
            'categories' => $categories
        ]);
    }

    public function storeCourse() {
        // 1. Save course info
        $courseId = $this->courseModel->create([
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'category_id' => $_POST['category_id'],
            'teacher_id' => $_SESSION['user_id']
        ]);

        // 2. Save chapters
        foreach ($_POST['chapters'] as $chapter) {
            $chapterId = $this->chapterModel->create([
                'course_id' => $courseId,
                'title' => $chapter['title'],
                'description' => $chapter['description']
            ]);
        }

        $_SESSION['success'] = "Cours créé avec succès";
        header('Location: /teacher/dashboard');
        exit;
    }
}