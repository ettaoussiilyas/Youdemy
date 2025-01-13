<?php
class UploadHelper {
    private $allowedVideoTypes = ['video/mp4', 'video/webm'];
    private $allowedDocTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    private $maxFileSize = 100000000; // 100MB

    public function uploadChapterContent($file, $courseId, $chapterId, $type) {
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Erreur upload");
        }

        // Check size
        if ($file['size'] > $this->maxFileSize) {
            throw new Exception("Fichier trop grand (max 100MB)");
        }

        // Check type
        $this->validateFileType($file, $type);

        // Create directory with full path
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/uploads/{$type}s/course_{$courseId}/chapter_{$chapterId}";
        
        // Debug
        error_log("Creating directory: " . $uploadDir);
        
        // Create directory recursively
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0777, true)) {
                throw new Exception("Impossible de créer le dossier d'upload");
            }
        }

        // Generate unique filename
        $fileName = uniqid() . '_' . basename($file['name']);
        $filePath = $uploadDir . '/' . $fileName;

        // Debug
        error_log("Uploading to: " . $filePath);

        // Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            throw new Exception("Échec de l'upload");
        }

        // Return relative path for database
        return [
            'file_path' => "uploads/{$type}s/course_{$courseId}/chapter_{$chapterId}/" . $fileName,
            'original_name' => $file['name']
        ];
    }

    private function validateFileType($file, $type) {
        if ($type === 'video' && !in_array($file['type'], $this->allowedVideoTypes)) {
            throw new Exception("Type de vidéo non autorisé");
        }
        if ($type === 'document' && !in_array($file['type'], $this->allowedDocTypes)) {
            throw new Exception("Type de document non autorisé");
        }
    }

    private function createUploadDirectory($courseId, $chapterId, $type) {
        $baseDir = "public/uploads/{$type}s/course_{$courseId}/chapter_{$chapterId}";
        if (!file_exists($baseDir)) {
            mkdir($baseDir, 0777, true);
        }
        return $baseDir;
    }
} 

?>