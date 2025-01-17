<?php
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Chapter.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Tag.php';
require_once __DIR__ . '/../helpers/UploadHelper.php';

class TeacherController extends BaseController {
    private $courseModel;
    private $chapterModel;
    private $categoryModel;
    private $tagModel;
    private $uploadHelper;
    
    public function __construct() {
        parent::__construct();
        $this->courseModel = new Course();
        $this->chapterModel = new Chapter();
        $this->categoryModel = new Category();
        $this->tagModel = new Tag();
        $this->uploadHelper = new UploadHelper();
    }
    
    public function dashboard() {
        if(!isset($_SESSION['user_id'])){
            return $this->render('auth/login', ['errors' => 'You must be logged in to access this page']);
        }
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
        $categories = $this->categoryModel->getAll();
        $tags = $this->tagModel->getAll();

        $this->renderTeacher('course/create', [
            'categories' => $categories,
            'tags' => $tags
        ]);
    }

    public function storeCourse() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validate thumbnail URL if provided
                $thumbnail = isset($_POST['thumbnail']) ? trim($_POST['thumbnail']) : null;
                if ($thumbnail && !filter_var($thumbnail, FILTER_VALIDATE_URL)) {
                    throw new Exception("Invalid thumbnail URL format");
                }

                $courseData = [
                    'title' => $_POST['title'],
                    'description' => $_POST['description'],
                    'teacher_id' => $_SESSION['user_id'],
                    'category_id' => $_POST['category_id'],
                    'thumbnail' => $thumbnail,  // Add thumbnail to course data
                    'tags' => isset($_POST['tags']) ? $_POST['tags'] : []
                ];

                $courseId = $this->courseModel->create($courseData);
                
                if ($courseId) {
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
                }
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                header('Location: /teacher/course/create');
                exit;
            }
        }

        $_SESSION['error'] = "Erreur lors de la création du cours";
        header('Location: /teacher/course/create');
        exit;
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

    public function editCourse($courseId = null) {
        // Vérifier si l'ID est passé dans l'URL
        if ($courseId === null) {
            $courseId = isset($_GET['id']) ? $_GET['id'] : null;
        }

        // Vérifier si on a un ID valide
        if (!$courseId) {
            $_SESSION['error'] = "ID du cours non spécifié";
            header('Location: /teacher/mycourses');
            exit;
        }

        $course = $this->courseModel->getById($courseId);
        
        // Vérifier si le cours existe
        if (!$course) {
            $_SESSION['error'] = "Cours non trouvé";
            header('Location: /teacher/mycourses');
            exit;
        }

        $categories = $this->categoryModel->getAll();
        $chapters = $this->chapterModel->getChaptersByCourseId($courseId);
        $tags = $this->tagModel->getAll();
        $courseTags = $this->courseModel->getCourseTags($courseId);

        $this->renderTeacher('course/edit', [
            'course' => $course,
            'categories' => $categories,
            'chapters' => $chapters,
            'tags' => $tags,
            'courseTags' => $courseTags
        ]);
    }

    public function updateCourse() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $courseId = $_POST['course_id'];
            $error = null;
            $success = null;
            
            try {
                // Mise à jour du cours
                $courseData = [
                    'id' => $courseId,
                    'title' => $_POST['title'],
                    'description' => $_POST['description'],
                    'category_id' => $_POST['category_id'],
                    'tags' => isset($_POST['tags']) ? $_POST['tags'] : []
                ];

                if (!$this->courseModel->update($courseData)) {
                    throw new Exception("Erreur lors de la mise à jour du cours");
                }

                // Mise à jour des tags
                $this->courseModel->updateTags($courseId, $courseData['tags']);

                // Traitement des nouveaux chapitres
                if (isset($_POST['new_chapters'])) {
                    foreach ($_POST['new_chapters'] as $index => $chapterData) {
                        $chapter = [
                            'course_id' => $courseId,
                            'title' => $chapterData['title'],
                            'description' => $chapterData['description']
                        ];

                        $chapterId = $this->chapterModel->create($chapter);
                        
                        if (!$chapterId) {
                            throw new Exception("Erreur lors de la création du chapitre");
                        }

                        // Traitement du fichier si présent
                        if (isset($_FILES['new_chapters']['name'][$index]['content']) 
                            && $_FILES['new_chapters']['error'][$index]['content'] === UPLOAD_ERR_OK) {
                            
                            if (!$this->handleChapterFileUpload(
                                $_FILES['new_chapters']['tmp_name'][$index]['content'],
                                $_FILES['new_chapters']['name'][$index]['content'],
                                $courseId,
                                $chapterId,
                                $chapterData['type']
                            )) {
                                throw new Exception("Erreur lors de l'upload du fichier");
                            }
                        }
                    }
                }

                $success = "Cours mis à jour avec succès";
                $this->renderTeacher('mycourses', [
                    'courses' => $this->courseModel->getTeacherCourses($_SESSION['user_id']),
                    'error' => null,
                    'success' => $success
                ]);

            } catch (Exception $e) {
                $error = $e->getMessage();
                $course = $this->courseModel->getById($courseId);
                $categories = $this->categoryModel->getAll();
                $chapters = $this->chapterModel->getChaptersByCourseId($courseId);
                $tags = $this->tagModel->getAll();
                $courseTags = $this->courseModel->getCourseTags($courseId);

                $this->renderTeacher('course/edit', [
                    'course' => $course,
                    'categories' => $categories,
                    'chapters' => $chapters,
                    'tags' => $tags,
                    'courseTags' => $courseTags,
                    'error' => $error,
                    'success' => null
                ]);
            }
        }
    }

    private function handleChapterFileUpload($tmpFile, $fileName, $courseId, $chapterId, $fileType) {
        try {
            $uploadDir = 'uploads/courses/' . $courseId . '/chapters/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $newFileName = uniqid() . '_' . $fileName;
            $filePath = $uploadDir . $newFileName;

            if (move_uploaded_file($tmpFile, $filePath)) {
                $contentData = [
                    'chapter_id' => $chapterId,
                    'title' => $fileName,
                    'type' => $fileType,
                    'file_path' => $filePath,
                    'original_name' => $fileName
                ];
                return $this->chapterModel->addContent($contentData);
            }
            return false;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
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

    public function deleteChapter() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $chapterId = isset($_POST['chapter_id']) ? $_POST['chapter_id'] : null;
            $courseId = isset($_POST['course_id']) ? $_POST['course_id'] : null;

            if (!$chapterId) {
                echo json_encode(['success' => false, 'message' => 'ID du chapitre manquant']);
                exit;
            }

            try {
                if ($this->chapterModel->delete($chapterId)) {
                    echo json_encode(['success' => true, 'message' => 'Chapitre supprimé avec succès']);
                } else {
                    throw new Exception("Erreur lors de la suppression du chapitre");
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            exit;
        }
    }

    public function createCourseForm() {
        $categories = $this->categoryModel->getAll();
        $tags = $this->tagModel->getAll();

        $this->renderTeacher('courses/create', [
            'categories' => $categories,
            'tags' => $tags
        ]);
    }
}