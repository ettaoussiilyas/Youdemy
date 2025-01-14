<?php
class Chapter extends Db {
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
} 