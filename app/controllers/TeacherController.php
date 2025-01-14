<?php
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Chapter.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../helpers/UploadHelper.php';

class TeacherController extends BaseController {
    private $courseModel;
    private $chapterModel;
    private $categoryModel;
    private $uploadHelper;
    
    public function __construct() {
        parent::__construct();
        $this->courseModel = new Course();
        $this->chapterModel = new Chapter();
        $this->categoryModel = new Category();
        $this->uploadHelper = new UploadHelper();
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
        try {
            // 1. Save course info
            $courseData = [
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'category_id' => $_POST['category_id'],
                'teacher_id' => $_SESSION['user_id']
            ];

            $courseId = $this->courseModel->create($courseData);

            if (!$courseId) {
                throw new Exception("Erreur lors de la création du cours");
            }

            // 2. Save chapters and their content
            if (isset($_POST['chapters']) && is_array($_POST['chapters'])) {
                foreach ($_POST['chapters'] as $index => $chapter) {
                    // Create chapter
                    $chapterData = [
                        'course_id' => $courseId,
                        'title' => $chapter['title'],
                        'description' => $chapter['description']
                    ];

                    $chapterId = $this->chapterModel->create($chapterData);
                    
                    if (!$chapterId) {
                        throw new Exception("Erreur lors de la création d'un chapitre");
                    }

                    // Handle file upload
                    if (isset($_FILES['chapters']['name'][$index]['content'])) {
                        // Restructure files array for this specific file
                        $file = [
                            'name' => $_FILES['chapters']['name'][$index]['content'],
                            'type' => $_FILES['chapters']['type'][$index]['content'],
                            'tmp_name' => $_FILES['chapters']['tmp_name'][$index]['content'],
                            'error' => $_FILES['chapters']['error'][$index]['content'],
                            'size' => $_FILES['chapters']['size'][$index]['content']
                        ];

                        // Upload file using helper
                        $uploadResult = $this->uploadHelper->uploadChapterContent(
                            $file,
                            $courseId,
                            $chapterId,
                            $chapter['type']
                        );

                        // Save content info to database
                        $contentData = [
                            'chapter_id' => $chapterId,
                            'title' => $chapter['title'],
                            'type' => $chapter['type'],
                            'file_path' => $uploadResult['file_path'],
                            'original_name' => $uploadResult['original_name']
                        ];

                        if (!$this->chapterModel->addContent($contentData)) {
                            throw new Exception("Erreur lors de l'ajout du contenu");
                        }
                    }
                }
            }

            $_SESSION['success'] = "Cours créé avec succès!";
            header("Location: /teacher/dashboard");
            exit;

        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /teacher/course/create');
            exit;
        }
    }

    public function myCourses() {
        $teacherId = $_SESSION['user_id'];
        $courses = $this->courseModel->getTeacherCourses($teacherId);
        
        $this->renderTeacher('mycourses', [
            'courses' => $courses,
            'error' => null,
            'success' => null
        ]);
    }

    public function deleteCourse() {
        try {
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            
            if (!$id) {
                $this->renderTeacher('mycourses', [
                    'courses' => $this->courseModel->getTeacherCourses($_SESSION['user_id']),
                    'error' => 'Course ID not provided',
                    'success' => null
                ]);
                return;
            }

            $teacherId = $_SESSION['user_id'];
            $course = $this->courseModel->getCourseById($id);
            
            if (!$course || $course['teacher_id'] != $teacherId) {
                $this->renderTeacher('mycourses', [
                    'courses' => $this->courseModel->getTeacherCourses($teacherId),
                    'error' => 'Course not found or unauthorized',
                    'success' => null
                ]);
                return;
            }

            if ($this->courseModel->deleteCourse($id)) {
                $this->renderTeacher('mycourses', [
                    'courses' => $this->courseModel->getTeacherCourses($teacherId),
                    'error' => null,
                    'success' => 'Course deleted successfully'
                ]);
            } else {
                $this->renderTeacher('mycourses', [
                    'courses' => $this->courseModel->getTeacherCourses($teacherId),
                    'error' => 'Error deleting course',
                    'success' => null
                ]);
            }

        } catch (Exception $e) {
            $this->renderTeacher('mycourses', [
                'courses' => $this->courseModel->getTeacherCourses($_SESSION['user_id']),
                'error' => $e->getMessage(),
                'success' => null
            ]);
        }
    }
}