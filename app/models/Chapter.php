<?php
class Chapter extends Db {
    public function __construct() {
        parent::__construct();
    }

    public function create($data) {
        try {
            $sql = "INSERT INTO chapters (course_id, title, description) 
                    VALUES (:course_id, :title, :description)";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':course_id' => $data['course_id'],
                ':title' => $data['title'],
                ':description' => $data['description']
            ]);

            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
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
        $sql = "SELECT * FROM chapter_content WHERE chapter_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$chapterId]);
        return $stmt->fetchAll();
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
} 