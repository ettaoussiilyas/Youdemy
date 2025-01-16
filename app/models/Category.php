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

    public function getTotalCategories() {
        try {
            $sql = "SELECT COUNT(*) as total FROM categories";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            error_log("Error getting total categories: " . $e->getMessage());
            return 0;
        }
    }

    public function getPopularCategories() {
        try {
            $sql = "SELECT 
                        c.name,
                        COUNT(co.id) as course_count,
                        COUNT(co.id) * 100.0 / (SELECT COUNT(*) FROM courses) as percentage
                    FROM categories c
                    LEFT JOIN courses co ON c.id = co.category_id
                    GROUP BY c.id, c.name
                    ORDER BY course_count DESC
                    LIMIT 5";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting popular categories: " . $e->getMessage());
            return [];
        }
    }

    public function getAll() {
        $sql = "SELECT * FROM categories ORDER BY name";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
