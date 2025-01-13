<?php
class Chapter extends Db {
    public function create($data) {
        $sql = "INSERT INTO chapters (course_id, title, description) 
                VALUES (?, ?, ?)";
        
        return $this->conn->prepare($sql)->execute([
            $data['course_id'],
            $data['title'],
            $data['description']
        ]);
    }

    public function addContent($data) {
        $sql = "INSERT INTO chapter_content 
                (chapter_id, title, type, file_path, original_name) 
                VALUES (?, ?, ?, ?, ?)";
        
        return $this->conn->prepare($sql)->execute([
            $data['chapter_id'],
            $data['title'],
            $data['type'],
            $data['file_path'],
            $data['original_name']
        ]);
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