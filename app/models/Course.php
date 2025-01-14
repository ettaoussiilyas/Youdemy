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
                c.teacher_id,
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

        public function getTeacherCoursesCount($teacherId) {
            $sql = "SELECT COUNT(*) FROM courses WHERE teacher_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$teacherId]);
            return $stmt->fetchColumn();
        }

        public function getTeacherTotalStudents($teacherId) {
            $sql = "SELECT COUNT(DISTINCT e.student_id) 
                    FROM courses c 
                    JOIN enrollments e ON c.id = e.course_id 
                    WHERE c.teacher_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$teacherId]);
            return $stmt->fetchColumn();
        }

        public function getTeacherRecentCourses($teacherId, $limit = 5) {
            $sql = "SELECT c.*, cat.name as category_name,
                    (SELECT COUNT(*) FROM enrollments e WHERE e.course_id = c.id) as student_count
                    FROM courses c
                    LEFT JOIN categories cat ON c.category_id = cat.id
                    WHERE c.teacher_id = ?
                    ORDER BY c.created_at DESC
                    LIMIT ?";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(1, $teacherId, PDO::PARAM_INT);
            $stmt->bindValue(2, $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        }

        public function getTeacherActiveCoursesCount($teacherId) {
            $sql = "SELECT COUNT(*) FROM courses WHERE teacher_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$teacherId]);
            return $stmt->fetchColumn() ?? 0;
        }

        public function create($data) {
            try {
                $sql = "INSERT INTO courses (title, description, category_id, teacher_id) 
                        VALUES (:title, :description, :category_id, :teacher_id)";
                
                $stmt = $this->conn->prepare($sql);
                
                $stmt->execute([
                    ':title' => $data['title'],
                    ':description' => $data['description'],
                    ':category_id' => $data['category_id'],
                    ':teacher_id' => $data['teacher_id']
                ]);

                return $this->conn->lastInsertId();
                
            } catch (PDOException $e) {
                error_log("Error creating course: " . $e->getMessage());
                return false;
            }
        }

        public function getTeacherCourses($teacherId) {
            $sql = "SELECT c.*, cat.name as category_name, 
                    (SELECT COUNT(*) FROM enrollments e WHERE e.course_id = c.id) as student_count 
                    FROM courses c 
                    LEFT JOIN categories cat ON c.category_id = cat.id 
                    WHERE c.teacher_id = ?
                    ORDER BY c.created_at DESC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$teacherId]);
            return $stmt->fetchAll();
        }

        public function deleteCourse($courseId) {
            try {
                $this->conn->beginTransaction();

                // 1. Get all chapters for this course
                $sql = "SELECT id FROM chapters WHERE course_id = ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([$courseId]);
                $chapters = $stmt->fetchAll();

                // 2. Delete content files from storage
                foreach ($chapters as $chapter) {
                    // Delete chapter content files
                    $sql = "SELECT file_path FROM chapter_content WHERE chapter_id = ?";
                    $stmt = $this->conn->prepare($sql);
                    $stmt->execute([$chapter['id']]);
                    $contents = $stmt->fetchAll();

                    foreach ($contents as $content) {
                        if (file_exists($content['file_path'])) {
                            unlink($content['file_path']); // Delete physical file
                        }
                    }

                    // Delete chapter content records
                    $sql = "DELETE FROM chapter_content WHERE chapter_id = ?";
                    $stmt = $this->conn->prepare($sql);
                    $stmt->execute([$chapter['id']]);
                }

                // 3. Delete chapters
                $sql = "DELETE FROM chapters WHERE course_id = ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([$courseId]);

                // 4. Delete enrollments
                $sql = "DELETE FROM enrollments WHERE course_id = ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([$courseId]);

                // 5. Finally delete the course
                $sql = "DELETE FROM courses WHERE id = ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([$courseId]);

                $this->conn->commit();
                return true;

            } catch (Exception $e) {
                $this->conn->rollBack();
                error_log("Error deleting course: " . $e->getMessage());
                return false;
            }
        }

        public function update($data) {
            try {
                $this->conn->beginTransaction();

                // Update course basic info
                $sql = "UPDATE courses 
                        SET title = :title, 
                            description = :description, 
                            category_id = :category_id 
                        WHERE id = :id";
                
                $stmt = $this->conn->prepare($sql);
                $result = $stmt->execute([
                    ':title' => $data['title'],
                    ':description' => $data['description'],
                    ':category_id' => $data['category_id'],
                    ':id' => $data['id']
                ]);

                if (!$result) {
                    throw new Exception("Error updating course");
                }

                $this->conn->commit();
                return true;

            } catch (Exception $e) {
                $this->conn->rollBack();
                error_log("Error updating course: " . $e->getMessage());
                return false;
            }
        }

        public function getTeacherStats($teacherId) {
            try {
                // Get total views/interactions per course
                $sql = "SELECT 
                        c.title,
                        COUNT(DISTINCT e.student_id) as student_count,
                        cat.name as category_name,
                        c.created_at,
                        (
                            SELECT COUNT(*) 
                            FROM chapters 
                            WHERE course_id = c.id
                        ) as chapter_count
                    FROM courses c
                    LEFT JOIN enrollments e ON c.id = e.course_id
                    LEFT JOIN categories cat ON c.category_id = cat.id
                    WHERE c.teacher_id = ?
                    GROUP BY c.id
                    ORDER BY student_count DESC";

                $stmt = $this->conn->prepare($sql);
                $stmt->execute([$teacherId]);
                $courseStats = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Get total stats
                $totalStats = [
                    'total_courses' => $this->getTeacherCoursesCount($teacherId),
                    'total_students' => $this->getTeacherTotalStudents($teacherId),
                    'total_chapters' => $this->getTeacherTotalChapters($teacherId),
                    'courses_by_category' => $this->getTeacherCoursesByCategory($teacherId)
                ];

                return [
                    'course_stats' => $courseStats,
                    'total_stats' => $totalStats
                ];
            } catch (Exception $e) {
                error_log("Error getting teacher stats: " . $e->getMessage());
                return false;
            }
        }

        private function getTeacherTotalChapters($teacherId) {
            $sql = "SELECT COUNT(*) as count 
                    FROM chapters ch
                    JOIN courses c ON ch.course_id = c.id
                    WHERE c.teacher_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$teacherId]);
            return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        }

        private function getTeacherCoursesByCategory($teacherId) {
            $sql = "SELECT 
                        cat.name,
                        COUNT(*) as count
                    FROM courses c
                    JOIN categories cat ON c.category_id = cat.id
                    WHERE c.teacher_id = ?
                    GROUP BY cat.id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$teacherId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

    }

?>