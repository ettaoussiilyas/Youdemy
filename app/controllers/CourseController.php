<?php

    require_once '../app/includes/autoloaderControllers.php';

    class CourseController extends BaseController {

        private $courseModel;

        public function __construct(){
            $this->courseModel = new Course();
        }

        public function getAllCourses(){
            $courses = $this->courseModel->getAllCourses();
            $this->render('student/courses', ['courses' => $courses]);
        }

        public function getCourseById($id) {
            if (!$id) {
                header('Location: /courses');
                exit;
            }
            
            // Get course details with chapters
            $course = $this->courseModel->getCourseWithDetails($id);
            if (!$course) {
                header('Location: /courses');
                exit;
            }
            
            // Get chapters with their content
            $chapters = $this->courseModel->getCourseChaptersWithContent($id);
            
            // Check enrollment
            $isEnrolled = false;
            if (isset($_SESSION['user_id'])) {
                $isEnrolled = $this->courseModel->isStudentEnrolled($_SESSION['user_id'], $id);
            }
            
            $this->render('components/courseView', [
                'course' => $course,
                'chapters' => $chapters,
                'isEnrolled' => $isEnrolled
            ]);
        }

        public function getCourseByCategory($category){
            $courses = $this->courseModel->getCourseByCategory($category);
            $this->render('course', ['courses' => $courses]);
        }

        public function getCourseByTeacher($teacher){
            $courses = $this->courseModel->getCourseByTeacher($teacher);
            $this->render('course', ['courses' => $courses]);
        }

        public function showHome() {
            // Get limited number of courses for homepage
            $courses = $this->courseModel->getAllCourses();
            $this->render('Home', ['courses' => $courses]);
        }
        


    }

?>