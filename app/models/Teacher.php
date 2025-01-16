<?php

require_once __DIR__ . '/../config/db.php';

class Teacher extends Db {

    public function __construct() {
        parent::__construct();
    }

    public function getTotalTeachers() {
        try {
            $sql = "SELECT COUNT(*) as total FROM users WHERE role = 'teacher'";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function getPendingTeachers() {
        try {
            $sql = "SELECT 
                        id, 
                        name, 
                        email, 
                        created_at 
                    FROM users 
                    WHERE role = 'teacher' 
                    AND status = 'review'
                    ORDER BY created_at DESC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getPendingTeachers: " . $e->getMessage());
            return [];
        }
    }

    public function approveTeacher($teacherId) {
        try {
            $sql = "UPDATE users 
                    SET status = 'active' 
                    WHERE id = ? AND role = 'teacher'";
            
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$teacherId]);
        } catch (PDOException $e) {
            error_log("Error approving teacher: " . $e->getMessage());
            return false;
        }
    }

    public function rejectTeacher($teacherId) {
        try {
            $sql = "UPDATE users 
                    SET status = 'rejected' 
                    WHERE id = ? AND role = 'teacher'";
            
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$teacherId]);
        } catch (PDOException $e) {
            error_log("Error rejecting teacher: " . $e->getMessage());
            return false;
        }
    }
}

?>