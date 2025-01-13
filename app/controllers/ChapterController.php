<?php

require_once __DIR__ . '/../../core/BaseController.php';
require_once __DIR__ . '/../models/Chapter.php';
require_once __DIR__ . '/../helpers/UploadHelper.php';

class ChapterController extends BaseController {
    private $chapterModel;
    private $uploadHelper;

    public function __construct() {
        BaseController::__construct();
        $this->chapterModel = new Chapter();
        $this->uploadHelper = new UploadHelper();
    }

    public function addContent() {
        try {


            // Debug au début de la méthode
            error_log("POST Data: " . print_r($_POST, true));
            error_log("FILES Data: " . print_r($_FILES, true));

            $chapterId = $_POST['chapter_id'];
            $courseId = $_POST['course_id'];
            $title = $_POST['title'];
            $type = $_POST['type'];

            // Debug: vérifier les valeurs
            var_dump($chapterId, $courseId, $title, $type);

            // Upload file
            $uploadResult = $this->uploadHelper->uploadChapterContent(
                $_FILES['content'],
                $courseId,
                $chapterId,
                $type
            );

            // Debug: voir le résultat de l'upload
            var_dump($uploadResult);

            // Save to database
            $contentData = [
                'chapter_id' => $chapterId,
                'title' => $title,
                'type' => $type,
                'file_path' => $uploadResult['file_path'],
                'original_name' => $uploadResult['original_name']
            ];

            $result = $this->chapterModel->addContent($contentData);

            // Debug: voir si l'insertion a réussi
            var_dump($result);

            // Redirect with success message
            $_SESSION['success'] = "Contenu ajouté avec succès";
            header("Location: /chapter/add-content/" . $chapterId);
            exit(); // Important: ajouter exit

        } catch (Exception $e) {
            error_log("Error in addContent: " . $e->getMessage());
            $_SESSION['error'] = $e->getMessage();
            header("Location: /chapter/add-content/" . $_POST['chapter_id']);
            exit();
        }
    }

    public function showAddContentForm($courseId = null, $chapterId = null) {
        // Get from URL parameters
        if (!$courseId) {
            $courseId = $_GET['course'] ?? null;
        }
        if (!$chapterId) {
            $chapterId = $_GET['chapter'] ?? null;
        }

        // Validate parameters
        if (!$courseId || !$chapterId) {
            $_SESSION['error'] = "Course ID et Chapter ID sont requis";
            header('Location: /courses');
            exit;
        }

        // Get chapter details
        $chapter = $this->chapterModel->getChapterById($chapterId);
        
        // Verify chapter belongs to course
        if (!$chapter || $chapter['course_id'] != $courseId) {
            $_SESSION['error'] = "Chapitre non trouvé ou n'appartient pas à ce cours";
            header('Location: /courses');
            exit;
        }

        // Pass data to view
        $this->render('teacher/chapter/add-content', [
            'chapter' => $chapter,
            'courseId' => $courseId
        ]);
    }

    public function showCreateForm() {
        $courseId = isset($_GET['course']) ? $_GET['course'] : null;
        
        if (!$courseId) {
            $_SESSION['error'] = "Course ID est requis";
            header('Location: /courses');
            exit;
        }

        $this->renderTeacher('chapter/create', [
            'courseId' => $courseId
        ]);
    }

    public function createChapter() {
        $courseId = $_POST['course_id'];
        $title = $_POST['title'];
        $description = $_POST['description'] ?? '';

        // Save to database
        $chapterId = $this->chapterModel->create([
            'course_id' => $courseId,
            'title' => $title,
            'description' => $description
        ]);

        if ($chapterId) {
            $_SESSION['success'] = "Chapitre créé avec succès";
            // Redirect to add content
            header("Location: /chapter/add-content?course={$courseId}&chapter={$chapterId}");
        } else {
            $_SESSION['error'] = "Erreur lors de la création du chapitre";
            header("Location: /chapter/create?course={$courseId}");
        }
        exit;
    }
} 