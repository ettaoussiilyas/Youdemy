<?php

require_once __DIR__ . '/../config/db.php';

class User extends Db {

    public function __construct() {
        parent::__construct();
    }

    public function getAllUsers() {
        try {
            $sql = "SELECT id, name, email, role, status FROM users";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching users: " . $e->getMessage());
            return [];
        }
    }

    public function deleteUser($userId) {
        try {
            $sql = "DELETE FROM users WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$userId]);
        } catch (PDOException $e) {
            error_log("Error deleting user: " . $e->getMessage());
            return false;
        }
    }

    public function updateUserStatus($userId, $newStatus) {
        try {
            $sql = "UPDATE users SET status = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$newStatus, $userId]);
        } catch (PDOException $e) {
            error_log("Error updating user status: " . $e->getMessage());
            return false;
        }
    }
}