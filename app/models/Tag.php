<?php
require_once __DIR__ . '/../config/db.php';

class Tag extends Db {
    private $db;

    public function __construct() {
        parent::__construct();
    }

    public function getAllTags() {
        try {
            $sql = "SELECT * FROM tags ORDER BY name";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching tags: " . $e->getMessage());
            return [];
        }
    }

    public function addTag($name) {
        try {
            $sql = "INSERT INTO tags (name) VALUES (?)";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$name]);
        } catch (PDOException $e) {
            error_log("Error adding tag: " . $e->getMessage());
            return false;
        }
    }

    public function deleteTag($id) {
        try {
            $sql = "DELETE FROM tags WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Error deleting tag: " . $e->getMessage());
            return false;
        }
    }

    public function getTotalTags() {
        try {
            $sql = "SELECT COUNT(*) as total FROM tags";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            error_log("Error getting total tags: " . $e->getMessage());
            return 0;
        }
    }

    public function getPopularTags() {
        try {
            $sql = "SELECT t.name, COUNT(ct.course_id) as count
                    FROM tags t
                    LEFT JOIN course_tags ct ON t.id = ct.tag_id
                    GROUP BY t.id, t.name
                    ORDER BY count DESC
                    LIMIT 10";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting popular tags: " . $e->getMessage());
            return [];
        }
    }

    public function getAll() {
        $sql = "SELECT * FROM tags ORDER BY name";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} 