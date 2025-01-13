<?php

    require_once __DIR__ . '/../config/db.php';

    class Course extends Db {

        public function __construct(){
            parent::__construct();
        }

        public function getAllCourses(){
            $query = "SELECT 
                        c.id, 
                        c.title, 
                        c.description, 
                        c.thumbnail,
                        cat.name as category_name,
                        u.name as teacher_name,
                        u.profile_image as teacher_image,
                        GROUP_CONCAT(DISTINCT t.name) as tags,
                        COUNT(DISTINCT e.student_id) as student_count
                        FROM courses c
                        LEFT JOIN categories cat ON c.category_id = cat.id
                        LEFT JOIN users u ON c.teacher_id = u.id
                        LEFT JOIN course_tags ct ON c.id = ct.course_id
                        LEFT JOIN tags t ON ct.tag_id = t.id
                        LEFT JOIN enrollments e ON c.id = e.course_id
                        GROUP BY c.id";

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

        public function getCourseWithDetails($courseId) {
            $sql = "SELECT 
                c.*,
                cat.name as category_name,
                u.name as teacher_name,
                u.profile_image as teacher_image,
                GROUP_CONCAT(DISTINCT t.name) as tags,
                (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id) as student_count,
                (SELECT COUNT(*) FROM chapters WHERE course_id = c.id) as chapter_count
            FROM courses c
            LEFT JOIN categories cat ON c.category_id = cat.id
            LEFT JOIN users u ON c.teacher_id = u.id
            LEFT JOIN course_tags ct ON c.id = ct.course_id
            LEFT JOIN tags t ON ct.tag_id = t.id
            WHERE c.id = ?
            GROUP BY c.id";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$courseId]);
            return $stmt->fetch();
        }

        public function getCourseChaptersWithContent($courseId) {
            $sql = "SELECT 
                ch.*,
                cc.id as content_id,
                cc.title as content_title,
                cc.type as content_type,
                cc.file_path,
                cc.original_name
            FROM chapters ch
            LEFT JOIN chapter_content cc ON ch.id = cc.chapter_id
            WHERE ch.course_id = ? AND cc.type = 'video'
            ORDER BY ch.id";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$courseId]);
            $results = $stmt->fetchAll();

            // Organize data by chapters
            $chapters = [];
            foreach ($results as $row) {
                $chapters[$row['id']] = [
                    'id' => $row['id'],
                    'title' => $row['title'],
                    'description' => $row['description'],
                    'video' => $row['content_id'] ? [
                        'id' => $row['content_id'],
                        'title' => $row['content_title'],
                        'file_path' => $row['file_path'],
                        'original_name' => $row['original_name']
                    ] : null
                ];
            }

            return array_values($chapters);
        }

    }

?>