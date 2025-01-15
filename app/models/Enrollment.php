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
                    AVG(progress) as avg_progress
                FROM enrollments
                WHERE student_id = ?";
                
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$studentId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function isStudentEnrolled($studentId, $courseId) {
        $sql = "SELECT COUNT(*) FROM enrollments WHERE student_id = ? AND course_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$studentId, $courseId]);
        return $stmt->fetchColumn() > 0;
    }

    public function enroll($studentId, $courseId) {
        $sql = "INSERT INTO enrollments (student_id, course_id, progress, last_accessed) 
                VALUES (?, ?, 0, NOW())";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$studentId, $courseId]);
    }
} 