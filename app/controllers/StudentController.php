<?php

require_once '../app/includes/autoloaderControllers.php';

class StudentController extends BaseController {
    private $courseModel;
    private $enrollmentModel;
    private $userModel;
    private $chapterModel;

    public function __construct() {
        $this->courseModel = new Course();
        $this->enrollmentModel = new Enrollment();
        $this->userModel = new User();
        $this->chapterModel = new Chapter();
    }

    public function dashboard() {
        $studentId = $_SESSION['user_id'];
        $enrolledCourses = $this->enrollmentModel->getStudentCourses($studentId);
        
        $this->renderStudent('dashboard', [
            'enrolledCourses' => $enrolledCourses
        ]);
    }

    public function myCourses() {
        $studentId = $_SESSION['user_id'];
        $courses = $this->enrollmentModel->getStudentCourses($studentId);
        
        $this->renderStudent('myCourses', [
            'courses' => $courses
        ]);
    }

    public function browseCourses() {
        $courses = $this->courseModel->getAllAvailable();
        
        $this->renderStudent('browse', [
            'courses' => $courses
        ]);
    }

    public function viewCourse($courseId) {
        $studentId = $_SESSION['user_id'];
        
        // Vérifier si l'étudiant est inscrit
        if (!$this->enrollmentModel->isStudentEnrolled($studentId, $courseId)) {
            $_SESSION['error'] = "Vous devez être inscrit pour accéder à ce cours";
            header('Location: /student/course/details/' . $courseId);
            exit;
        }

        try {
            // Récupérer le cours avec ses chapitres et contenus
            $course = $this->courseModel->getCourseWithChapters($courseId);
            
            // Pour chaque chapitre, récupérer son contenu
            foreach ($course['chapters'] as &$chapter) {
                $content = $this->chapterModel->getChapterContent($chapter['id']);
                if ($content) {
                    $chapter['content'] = $content;
                }
            }

            $this->renderStudent('course/view', [
                'course' => $course,
                'activeChapter' => isset($course['chapters'][0]) ? $course['chapters'][0]['id'] : null
            ]);

        } catch (Exception $e) {
            $_SESSION['error'] = "Erreur lors du chargement du cours";
            header('Location: /student/dashboard');
            exit;
        }
    }

    public function profile() {
        $studentId = $_SESSION['user_id'];
        $profile = $this->userModel->getById($studentId);
        $stats = $this->enrollmentModel->getStudentStats($studentId);
        
        $this->renderStudent('profile', [
            'profile' => $profile,
            'stats' => $stats
        ]);
    }

    public function courseDetails($courseId) {
        $course = $this->courseModel->getCourseWithChapters($courseId);
        $isEnrolled = $this->enrollmentModel->isStudentEnrolled($_SESSION['user_id'], $courseId);
        $teacherInfo = $this->userModel->getById($course['teacher_id']);
        
        $this->renderStudent('course/details', [
            'course' => $course,
            'isEnrolled' => $isEnrolled,
            'teacher' => $teacherInfo
        ]);
    }

    public function enrollCourse($courseId) {
        $studentId = $_SESSION['user_id'];
        
        // Vérifier si l'étudiant n'est pas déjà inscrit
        if ($this->enrollmentModel->isStudentEnrolled($studentId, $courseId)) {
            $_SESSION['error'] = "You are already enrolled in this course";
            header('Location: /student/course/details/' . $courseId);
            exit;
        }

        // Tenter l'inscription
        if ($this->enrollmentModel->enroll($studentId, $courseId)) {
            $_SESSION['success'] = "You are enrolled in this course";
            header('Location: /student/course/details/' . $courseId);
            exit;
        }

        // En cas d'erreur
        $_SESSION['error'] = "An error occurred during enrollment";
        header('Location: /student/course/details/' . $courseId);
        exit;
    }
}
