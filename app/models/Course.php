<?php

    require_once __DIR__ . '/../config/db.php';

    class Course extends Db {

        public function __construct(){
            parent::__construct();
        }

        public function getAllCourses(){
            $query = "
                SELECT 
                    c.id,
                    c.title,
                    c.description,
                    c.thumbnail,
                    cat.name as category_name,
                    u.name as teacher_name,
                    u.profile_image as teacher_image,
                    COUNT(DISTINCT e.id) as student_count,
                    GROUP_CONCAT(t.name) as tags
                FROM courses c
                INNER JOIN users u ON c.teacher_id = u.id
                INNER JOIN categories cat ON c.category_id = cat.id
                LEFT JOIN enrollments e ON c.id = e.course_id
                LEFT JOIN course_tags ct ON c.id = ct.course_id
                LEFT JOIN tags t ON ct.tag_id = t.id
                GROUP BY c.id
            ";
            $result = $this->conn->query($query);
            return $result;
        }

        public function getCourseById($id){
            $query = "
            SELECT 
                c.id,
                c.title,
                c.description,
                c.thumbnail,
                cat.name as category_name,
                u.name as teacher_name,
                u.profile_image as teacher_image,
                COUNT(DISTINCT e.id) as student_count,
                GROUP_CONCAT(t.name) as tags
            FROM courses c
                INNER JOIN users u ON c.teacher_id = u.id
                INNER JOIN categories cat ON c.category_id = cat.id
                LEFT JOIN enrollments e ON c.id = e.course_id
                LEFT JOIN course_tags ct ON c.id = ct.course_id
                LEFT JOIN tags t ON ct.tag_id = t.id 
            WHERE c.id = ?
            GROUP BY c.id
            ";
            
            $stmt = $this->conn->prepare($query);
            // $stmt->bind_param("i", $id);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function getCourseByCategory($category){
            $sql = "";
            $result = $this->query($sql);
            return $result;
        }

        public function isStudentEnrolled($studentId, $courseId) {
            $sql = "SELECT COUNT(*) as count 
                    FROM enrollments 
                    WHERE student_id = ? AND course_id = ?";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$studentId, $courseId]);
            $result = $stmt->fetch();
            
            return $result['count'] > 0;
        }


    }

?>