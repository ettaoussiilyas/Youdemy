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
            $this->conn->beginTransaction();

            // Supprimer d'abord les enregistrements liés dans d'autres tables
            $tables = [
                'enrollments' => 'student_id',
                'notifications' => 'recipient_id',
                'courses' => 'teacher_id'
            ];

            foreach ($tables as $table => $column) {
                $sql = "DELETE FROM $table WHERE $column = ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([$userId]);
            }

            // Enfin, supprimer l'utilisateur
            $sql = "DELETE FROM users WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([$userId]);

            if ($result) {
                $this->conn->commit();
                return true;
            }

            $this->conn->rollBack();
            return false;

        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error in deleteUser: " . $e->getMessage());
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

    public function getTotalUsers() {
        try {
            $sql = "SELECT COUNT(*) as total FROM users";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            error_log("Error getting total users: " . $e->getMessage());
            return 0;
        }
    }

    public function getGrowthRate() {
        try {
            $sql = "SELECT 
                        (COUNT(CASE WHEN created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH) THEN 1 END) * 100.0 / 
                         NULLIF(COUNT(CASE WHEN created_at >= DATE_SUB(NOW(), INTERVAL 2 MONTH) 
                                          AND created_at < DATE_SUB(NOW(), INTERVAL 1 MONTH) THEN 1 END), 0)) - 100 
                    as growth_rate 
                    FROM users";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return round($result['growth_rate'], 1);
        } catch (PDOException $e) {
            error_log("Error calculating user growth rate: " . $e->getMessage());
            return 0;
        }
    }

    public function getUserByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // public function createUser($name, $email, $password, $role){
    //     $sql = "INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, ?, ?)";
    //     $stmt = $this->conn->prepare($sql);
    //     if($role === 'teacher'){
    //         return $stmt->execute([$name, $email, $password, $role, 'review']);
    //     }else{
    //         return $stmt->execute([$name, $email, $password, $role, 'active']);
    //     }
    // }
    public function createUser($name, $email, $password, $role) {
        try {
            $sql = "INSERT INTO users (name, email, password, role, status) 
                    VALUES (:name, :email, :password, :role, :status)";
            
            $stmt = $this->conn->prepare($sql);
            $status = ($role === 'teacher') ? 'review' : 'active';
            
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':password' => $password,
                ':role' => $role,
                ':status' => $status
            ]);
            
            return $this->conn->lastInsertId(); // Return the new user's ID
        } catch(PDOException $e) {
            error_log("Error creating user: " . $e->getMessage());
            return false;
        }
    }

    public function getStatus($id){
        $sql = "SELECT status FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getUserById($id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error fetching user: " . $e->getMessage());
            return false;
        }
    }

}