<?php

require_once '../app/includes/autoloaderControllers.php';

class StudentController extends BaseController {
    private $courseModel;
    private $enrollmentModel;
    private $userModel;

    public function __construct() {
        $this->courseModel = new Course();
        $this->enrollmentModel = new Enrollment();
        $this->userModel = new User();
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
        $course = $this->courseModel->getCourseWithChapters($courseId);
        $progress = $this->enrollmentModel->getStudentProgress($_SESSION['user_id'], $courseId);
        
        $this->renderStudent('course/view', [
            'course' => $course,
            'progress' => $progress
        ]);
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
        
        $this->renderStudent('course/details', [
            'course' => $course,
            'isEnrolled' => $isEnrolled
        ]);
    }

    public function enrollCourse($courseId) {
        if($this->enrollmentModel->enroll($_SESSION['user_id'], $courseId)) {
            header('Location: /student/course/' . $courseId);
            exit;
        }
        // Handle error
        header('Location: /student/browse');
        exit;
    }
}
