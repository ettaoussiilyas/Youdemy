<?php
class Chapter extends Db {
    public function __construct() {
        parent::__construct();
    }

    public function create($data) {
        try {
            $this->conn->beginTransaction();

            $sql = "INSERT INTO chapters (course_id, title, description) 
                    VALUES (:course_id, :title, :description)";
            
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([
                ':course_id' => $data['course_id'],
                ':title' => $data['title'],
                ':description' => $data['description']
            ]);

            if (!$result) {
                throw new Exception("Erreur lors de la création du chapitre");
            }

            $chapterId = $this->conn->lastInsertId();
            $this->conn->commit();
            return $chapterId;

        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error creating chapter: " . $e->getMessage());
            return false;
        }
    }

    public function addContent($data) {
        try {
            $sql = "INSERT INTO chapter_content (chapter_id, title, type, file_path, original_name) 
                    VALUES (:chapter_id, :title, :type, :file_path, :original_name)";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':chapter_id' => $data['chapter_id'],
                ':title' => $data['title'],
                ':type' => $data['type'],
                ':file_path' => $data['file_path'],
                ':original_name' => $data['original_name']
            ]);

            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error adding chapter content: " . $e->getMessage());
            return false;
        }
    }

    public function getChapterContent($chapterId) {
        try {
            $sql = "SELECT * FROM chapter_content WHERE chapter_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$chapterId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting chapter content: " . $e->getMessage());
            return null;
        }
    }

    public function getChapterById($id) {
        $sql = "SELECT c.*, co.title as course_title 
                FROM chapters c 
                JOIN courses co ON c.course_id = co.id 
                WHERE c.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getCourseChapters($courseId) {
        $query = "SELECT 
                    ch.*,
                    cc.type as content_type,
                    cc.file_path
                 FROM chapters ch
                 LEFT JOIN chapter_content cc ON ch.id = cc.chapter_id
                 WHERE ch.course_id = ?
                 ORDER BY ch.id";
                 
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$courseId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($data) {
        try {
            $this->conn->beginTransaction();

            $sql = "UPDATE chapters 
                    SET title = :title, 
                        description = :description 
                    WHERE id = :id";
            
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([
                ':title' => $data['title'],
                ':description' => $data['description'],
                ':id' => $data['id']
            ]);

            if (!$result) {
                throw new Exception("Error updating chapter");
            }

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error updating chapter: " . $e->getMessage());
            return false;
        }
    }

    public function updateContent($data) {
        try {
            $this->conn->beginTransaction();

            $sql = "UPDATE chapter_content 
                    SET file_path = :file_path,
                        original_name = :original_name 
                    WHERE chapter_id = :chapter_id";
            
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([
                ':file_path' => $data['file_path'],
                ':original_name' => $data['original_name'],
                ':chapter_id' => $data['chapter_id']
            ]);

            if (!$result) {
                throw new Exception("Error updating chapter content");
            }

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error updating chapter content: " . $e->getMessage());
            return false;
        }
    }

    public function delete($chapterId) {
        try {
            $this->conn->beginTransaction();

            // Récupérer les informations du contenu pour supprimer le fichier
            $stmt = $this->conn->prepare("
                SELECT file_path 
                FROM chapter_content 
                WHERE chapter_id = ?
            ");
            $stmt->execute([$chapterId]);
            $content = $stmt->fetch(PDO::FETCH_ASSOC);

            // Supprimer le fichier physique si existe
            if ($content && !empty($content['file_path']) && file_exists($content['file_path'])) {
                unlink($content['file_path']);
            }

            // Supprimer le contenu de la base de données
            $stmt = $this->conn->prepare("DELETE FROM chapter_content WHERE chapter_id = ?");
            $stmt->execute([$chapterId]);

            // Supprimer le chapitre
            $stmt = $this->conn->prepare("DELETE FROM chapters WHERE id = ?");
            $stmt->execute([$chapterId]);

            $this->conn->commit();
            return true;

        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error deleting chapter: " . $e->getMessage());
            return false;
        }
    }

    public function getById($chapterId) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM chapters WHERE id = ?");
            $stmt->execute([$chapterId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getChaptersByCourseId($courseId) {
        try {
            $stmt = $this->conn->prepare("
                SELECT ch.*, 
                       cc.id as content_id,
                       cc.title as content_title,
                       cc.type as content_type,
                       cc.file_path,
                       cc.original_name
                FROM chapters ch
                LEFT JOIN chapter_content cc ON ch.id = cc.chapter_id
                WHERE ch.course_id = ?
                ORDER BY ch.id ASC
            ");
            
            $stmt->execute([$courseId]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Organiser les données par chapitre
            $chapters = [];
            foreach ($results as $row) {
                $chapterId = $row['id'];
                if (!isset($chapters[$chapterId])) {
                    $chapters[$chapterId] = [
                        'id' => $row['id'],
                        'title' => $row['title'],
                        'description' => $row['description'],
                        'content' => null
                    ];
                }

                if ($row['content_id']) {
                    $chapters[$chapterId]['content'] = [
                        'id' => $row['content_id'],
                        'title' => $row['content_title'],
                        'type' => $row['content_type'],
                        'file_path' => $row['file_path'],
                        'original_name' => $row['original_name']
                    ];
                }
            }

            return array_values($chapters);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getCourseWithChapters($courseId) {
        try {
            // Récupérer les informations du cours
            $sql = "SELECT c.*, u.name as teacher_name 
                    FROM courses c 
                    LEFT JOIN users u ON c.teacher_id = u.id 
                    WHERE c.id = ?";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$courseId]);
            $course = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$course) {
                return null;
            }

            // Récupérer les chapitres du cours
            $sql = "SELECT ch.*, cc.type as content_type, cc.file_path, cc.original_name 
                    FROM chapters ch 
                    LEFT JOIN chapter_content cc ON ch.id = cc.chapter_id 
                    WHERE ch.course_id = ? 
                    ORDER BY ch.id ASC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$courseId]);
            $course['chapters'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $course;
        } catch (PDOException $e) {
            error_log("Error getting course with chapters: " . $e->getMessage());
            return null;
        }
    }
} 