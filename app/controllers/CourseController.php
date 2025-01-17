<?php

    require_once '../app/includes/autoloaderControllers.php';

    class CourseController extends BaseController {

        private $courseModel;
        private $categoryModel;
        private $tagModel;

        public function __construct(){
            parent::__construct();
            $this->courseModel = new Course();
            $this->categoryModel = new Category();
            $this->tagModel = new Tag();
        }

        public function getAllCourses(){
            $courses = $this->courseModel->getAllCourses();
            $this->render('student/courses', ['courses' => $courses]);
        }

        public function getCourseById($id) {
            try {
                // جلب معلومات الكورس
                $course = $this->courseModel->getCourseWithDetails($id);
                
                if (!$course) {
                    header('Location: /home');
                    exit;
                }
                
                // get the chapters with the content
                $chapters = $this->courseModel->getCourseChaptersWithContent($id);
                
                // check if the student is enrolled (if he is)
                $isEnrolled = false;
                if (isset($_SESSION['user_id'])) {
                    $isEnrolled = $this->courseModel->isStudentEnrolled($_SESSION['user_id'], $id);
                }
                
                // render the course details page
                $this->render('course/details', [
                    'course' => $course,
                    'chapters' => $chapters,
                    'isEnrolled' => $isEnrolled
                ]);
                
            } catch (Exception $e) {
                $_SESSION['error'] = "Une erreur s'est produite.";
                header('Location: /home');
                exit;
            }
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

        public function filterCourses() {
            $search = $_GET['search'] ?? '';
            $category = $_GET['category'] ?? '';
            $tag = $_GET['tag'] ?? '';
            
            $courses = $this->courseModel->searchCourses($search, $category, $tag);
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode($courses);
        }

        public function browseCourses() {
            try {
                // get all courses
                $courses = $this->courseModel->getAllCourses();
                
                // get all categories and tags for filtering
                $categories = $this->categoryModel->getAll();
                $tags = $this->tagModel->getAll();
                
                // render the page
                $this->render('course/browse', [
                    'courses' => $courses,
                    'categories' => $categories,
                    'tags' => $tags
                ]);
            } catch (Exception $e) {
                $_SESSION['error'] = "Une erreur s'est produite.";
                header('Location: /home');
                exit;
            }
        }
        


    }

?>