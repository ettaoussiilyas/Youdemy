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
            
            $course = $this->courseModel->getCourseById($id);
            if (!$course) {
                header('Location: /courses');
                exit;
            }
            
            // Vérifier si l'utilisateur est connecté et inscrit au cours
            $isEnrolled = false;
            if (isset($_SESSION['user_id'])) {
                $isEnrolled = $this->courseModel->isStudentEnrolled($_SESSION['user_id'], $id);
            }
            
            $this->render('components/couresView', [
                'course' => $course,
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
        


    }

?>