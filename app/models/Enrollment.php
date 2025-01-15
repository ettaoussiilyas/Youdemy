<?php

require_once __DIR__ . '/../config/db.php';
class Enrollment extends Db {

    public function __construct(){
        parent::__construct();
    }

    public function getStudentCourses($studentId) {
        $sql = "SELECT 
                    c.*,
                    e.progress,
                    e.last_accessed,
                    (SELECT COUNT(*) FROM chapters WHERE course_id = c.id) as chapter_count
                FROM enrollments e
                JOIN courses c ON e.course_id = c.id
                WHERE e.student_id = ?
                ORDER BY e.last_accessed DESC";
                
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStudentProgress($studentId, $courseId) {
        $sql = "SELECT progress FROM enrollments 
                WHERE student_id = ? AND course_id = ?";
                
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$studentId, $courseId]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['progress'] ?? 0;
    }

    public function getStudentStats($studentId) {
        $sql = "SELECT 
                    COUNT(DISTINCT course_id) as total_courses,
                    AVG(progress) as avg_progress,
                    MAX(last_accessed) as last_activity
                FROM enrollments
                WHERE student_id = ?";
                
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$studentId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function isStudentEnrolled($studentId, $courseId) {
        $stmt = $this->conn->prepare("
            SELECT COUNT(*) 
            FROM enrollments 
            WHERE student_id = ? AND course_id = ?
        ");
        $stmt->execute([$studentId, $courseId]);
        return $stmt->fetchColumn() > 0;
    }

    public function enroll($studentId, $courseId) {
        try {
            // Vérifier si le cours existe
            $courseCheck = $this->conn->prepare("SELECT id FROM courses WHERE id = ?");
            $courseCheck->execute([$courseId]);
            if (!$courseCheck->fetch()) {
                return false;
            }

            // Insérer l'inscription
            $stmt = $this->conn->prepare("
                INSERT INTO enrollments (student_id, course_id, enrollment_date)
                VALUES (?, ?, NOW())
            ");
            
            return $stmt->execute([$studentId, $courseId]);
        } catch (PDOException $e) {
            // Log l'erreur si nécessaire
            return false;
        }
    }
} 