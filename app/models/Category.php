<?php

class Category extends Db {
    public function getAllCategories() {
        try {
            $sql = "SELECT * FROM categories ORDER BY name";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching categories: " . $e->getMessage());
            return [];
        }
    }

    public function addCategory($name, $description) {
        try {
            $sql = "INSERT INTO categories (name, description) VALUES (?, ?)";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$name, $description]);
        } catch (PDOException $e) {
            error_log("Error adding category: " . $e->getMessage());
            return false;
        }
    }

    public function deleteCategory($id) {
        try {
            $sql = "DELETE FROM categories WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Error deleting category: " . $e->getMessage());
            return false;
        }
    }
}
