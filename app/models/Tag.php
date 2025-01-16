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
} 