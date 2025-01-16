<?php


require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Chapter.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Tag.php';
require_once __DIR__ . '/../models/User.php';

class HomeController extends BaseController {

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
        $this->courseModel = new Course();
        $this->chapterModel = new Chapter();
        $this->categoryModel = new Category();
        $this->tagModel = new Tag();
        $this->studentModel = new Student();
        $this->teacherModel = new Teacher();
        $this->enrollmentModel = new Enrollment();
        $this->userModel = new User();
   
    }

    public function index() {
        try {
            $courses = $this->courseModel->getPopularCourses();
            $categories = $this->categoryModel->getAllCategories();
            $tags = $this->tagModel->getAllTags();

            $this->render('Home', [
                'courses' => $courses,
                'categories' => $categories,
                'tags' => $tags
            ]);
        } catch (Exception $e) {
            // Gérer l'erreur
        }
    }
} 