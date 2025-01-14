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

    public function editCourse() {
        try {
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            
            if (!$id) {
                $this->renderTeacher('mycourses', [
                    'courses' => $this->courseModel->getTeacherCourses($_SESSION['user_id']),
                    'error' => 'ID du cours non fourni',
                    'success' => null
                ]);
                return;
            }

            $teacherId = $_SESSION['user_id'];
            $course = $this->courseModel->getCourseById($id);
            
            if (!$course || $course['teacher_id'] != $teacherId) {
                $this->renderTeacher('mycourses', [
                    'courses' => $this->courseModel->getTeacherCourses($teacherId),
                    'error' => 'Cours introuvable ou non autorisé',
                    'success' => null
                ]);
                return;
            }

            $categories = $this->categoryModel->getAll();
            $chapters = $this->chapterModel->getCourseChapters($id);
            
            $this->renderTeacher('course/edit', [
                'course' => $course,
                'categories' => $categories,
                'chapters' => $chapters,
                'error' => null
            ]);

        } catch (Exception $e) {
            $this->renderTeacher('mycourses', [
                'courses' => $this->courseModel->getTeacherCourses($_SESSION['user_id']),
                'error' => $e->getMessage(),
                'success' => null
            ]);
        }
    }

    public function updateCourse() {
        try {
            // Get course ID from POST data
            $id = isset($_POST['course_id']) ? $_POST['course_id'] : null;
            
            if (!$id) {
                throw new Exception('Course ID not provided');
            }

            $teacherId = $_SESSION['user_id'];
            $course = $this->courseModel->getCourseById($id);
            
            if (!$course || $course['teacher_id'] != $teacherId) {
                throw new Exception('Course not found or unauthorized');
            }

            // 1. Update course info
            $courseData = [
                'id' => $id,
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'category_id' => $_POST['category_id']
            ];

            if (!$this->courseModel->update($courseData)) {
                throw new Exception('Error updating course');
            }

            // 2. Update existing chapters
            if (isset($_POST['existing_chapters'])) {
                foreach ($_POST['existing_chapters'] as $chapterId => $chapter) {
                    $chapterData = [
                        'id' => $chapterId,
                        'title' => $chapter['title'],
                        'description' => $chapter['description']
                    ];

                    if (!$this->chapterModel->update($chapterData)) {
                        throw new Exception('Error updating chapter');
                    }

                    // Handle new content file if uploaded
                    if (isset($_FILES['existing_chapters']['name'][$chapterId]['content']) &&
                        $_FILES['existing_chapters']['error'][$chapterId]['content'] === UPLOAD_ERR_OK) {
                        
                        $file = [
                            'name' => $_FILES['existing_chapters']['name'][$chapterId]['content'],
                            'type' => $_FILES['existing_chapters']['type'][$chapterId]['content'],
                            'tmp_name' => $_FILES['existing_chapters']['tmp_name'][$chapterId]['content'],
                            'error' => $_FILES['existing_chapters']['error'][$chapterId]['content'],
                            'size' => $_FILES['existing_chapters']['size'][$chapterId]['content']
                        ];

                        $uploadResult = $this->uploadHelper->uploadChapterContent($file, $id, $chapterId);
                        
                        $contentData = [
                            'chapter_id' => $chapterId,
                            'file_path' => $uploadResult['file_path'],
                            'original_name' => $uploadResult['original_name']
                        ];

                        if (!$this->chapterModel->updateContent($contentData)) {
                            throw new Exception('Error updating chapter content');
                        }
                    }
                }
            }

            // 3. Add new chapters
            if (isset($_POST['new_chapters'])) {
                foreach ($_POST['new_chapters'] as $index => $chapter) {
                    $chapterData = [
                        'course_id' => $id,
                        'title' => $chapter['title'],
                        'description' => $chapter['description']
                    ];

                    $chapterId = $this->chapterModel->create($chapterData);
                    
                    if (!$chapterId) {
                        throw new Exception('Error creating new chapter');
                    }

                    // Handle file upload for new chapter
                    if (isset($_FILES['new_chapters']['name'][$index]['content'])) {
                        $file = [
                            'name' => $_FILES['new_chapters']['name'][$index]['content'],
                            'type' => $_FILES['new_chapters']['type'][$index]['content'],
                            'tmp_name' => $_FILES['new_chapters']['tmp_name'][$index]['content'],
                            'error' => $_FILES['new_chapters']['error'][$index]['content'],
                            'size' => $_FILES['new_chapters']['size'][$index]['content']
                        ];

                        $uploadResult = $this->uploadHelper->uploadChapterContent(
                            $file,
                            $id,
                            $chapterId,
                            $chapter['type']
                        );

                        $contentData = [
                            'chapter_id' => $chapterId,
                            'title' => $chapter['title'],
                            'type' => $chapter['type'],
                            'file_path' => $uploadResult['file_path'],
                            'original_name' => $uploadResult['original_name']
                        ];

                        if (!$this->chapterModel->addContent($contentData)) {
                            throw new Exception('Error adding chapter content');
                        }
                    }
                }
            }

            $this->renderTeacher('mycourses', [
                'courses' => $this->courseModel->getTeacherCourses($teacherId),
                'error' => null,
                'success' => 'Course updated successfully'
            ]);

        } catch (Exception $e) {
            $this->renderTeacher('course/edit', [
                'course' => $course,
                'categories' => $this->categoryModel->getAll(),
                'chapters' => $this->chapterModel->getCourseChapters($id),
                'error' => $e->getMessage()
            ]);
        }
    }

    public function showStats() {
        try {
            $teacherId = $_SESSION['user_id'];
            $stats = $this->courseModel->getTeacherStats($teacherId);
            
            if (!$stats) {
                throw new Exception('Error fetching statistics');
            }

            $this->renderTeacher('stats', [
                'totalCourses' => $stats['total_stats']['total_courses'],
                'totalStudents' => $stats['total_stats']['total_students'],
                'totalChapters' => $stats['total_stats']['total_chapters'],
                'coursesByCategory' => $stats['total_stats']['courses_by_category'],
                'courseStats' => $stats['course_stats']
            ]);

        } catch (Exception $e) {
            $this->renderTeacher('stats', [
                'error' => $e->getMessage()
            ]);
        }
    }
}