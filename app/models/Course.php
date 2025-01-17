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
                $sql = "INSERT INTO courses (
                    title, 
                    description, 
                    teacher_id, 
                    category_id, 
                    thumbnail,
                    created_at
                ) VALUES (
                    :title, 
                    :description, 
                    :teacher_id, 
                    :category_id, 
                    :thumbnail,
                    NOW()
                )";
                
                $stmt = $this->conn->prepare($sql);
                $result = $stmt->execute([
                    ':title' => $data['title'],
                    ':description' => $data['description'],
                    ':teacher_id' => $data['teacher_id'],
                    ':category_id' => $data['category_id'],
                    ':thumbnail' => !empty($data['thumbnail']) ? $data['thumbnail'] : 'https://placehold.co/600x400?text=Course'
                ]);

                if (!$result) {
                    throw new Exception("Failed to create course");
                }

                $courseId = $this->conn->lastInsertId();

                // Use Tag model for handling tags
                $tagModel = new Tag();
                
                // Handle tags if present
                if (isset($data['tags']) && is_array($data['tags'])) {
                    foreach ($data['tags'] as $tagId) {
                        $tagModel->addTag($courseId, $tagId);
                    }
                }

                return $courseId;
            } catch (PDOException $e) {
                error_log("Error in create course: " . $e->getMessage());
                throw new Exception("Error creating course");
            }
        }

        public function getTeacherCourses($teacherId) {
            try {
                $stmt = $this->conn->prepare("
                    SELECT c.*, cat.name as category_name,
                           COUNT(DISTINCT e.id) as student_count
                    FROM courses c
                    LEFT JOIN categories cat ON c.category_id = cat.id
                    LEFT JOIN enrollments e ON c.id = e.course_id
                    WHERE c.teacher_id = ?
                    GROUP BY c.id
                ");
                
                $stmt->execute([$teacherId]);
                $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Récupérer les tags pour chaque cours
                foreach ($courses as &$course) {
                    $stmt = $this->conn->prepare("
                        SELECT t.* 
                        FROM tags t
                        JOIN course_tags ct ON t.id = ct.tag_id
                        WHERE ct.course_id = ?
                    ");
                    $stmt->execute([$course['id']]);
                    $course['tags'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                }

                return $courses;
            } catch (PDOException $e) {
                return [];
            }
        }

        public function deleteCourse($courseId) {
            try {
                $this->conn->beginTransaction();

                // 1. Delete course tags first
                $sql = "DELETE FROM course_tags WHERE course_id = ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([$courseId]);

                // 2. Check if course has chapters
                $sql = "SELECT id FROM chapters WHERE course_id = ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([$courseId]);
                $chapters = $stmt->fetchAll();

                if (!empty($chapters)) {
                    // Handle chapters if they exist
                    foreach ($chapters as $chapter) {
                        // Delete chapter content files
                        $sql = "SELECT file_path FROM chapter_content WHERE chapter_id = ?";
                        $stmt = $this->conn->prepare($sql);
                        $stmt->execute([$chapter['id']]);
                        $contents = $stmt->fetchAll();

                        foreach ($contents as $content) {
                            if (!empty($content['file_path']) && file_exists($content['file_path'])) {
                                unlink($content['file_path']); // Delete physical file
                            }
                        }

                        // Delete chapter content records
                        $sql = "DELETE FROM chapter_content WHERE chapter_id = ?";
                        $stmt = $this->conn->prepare($sql);
                        $stmt->execute([$chapter['id']]);
                    }

                    // Delete all chapters
                    $sql = "DELETE FROM chapters WHERE course_id = ?";
                    $stmt = $this->conn->prepare($sql);
                    $stmt->execute([$courseId]);
                }

                // 3. Delete enrollments
                $sql = "DELETE FROM enrollments WHERE course_id = ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([$courseId]);

                // 4. Delete the course
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
                $sql = "UPDATE courses 
                        SET title = :title, 
                            description = :description, 
                            category_id = :category_id,
                            thumbnail = :thumbnail
                        WHERE id = :id";

                $stmt = $this->conn->prepare($sql);
                
                return $stmt->execute([
                    ':title' => $data['title'],
                    ':description' => $data['description'],
                    ':category_id' => $data['category_id'],
                    ':thumbnail' => $data['thumbnail'],
                    ':id' => $data['id']
                ]);
            } catch (PDOException $e) {
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

        public function getCourseWithChapters($courseId) {
            $sql = "SELECT 
                c.*,
                u.name as teacher_name,
                (SELECT COUNT(*) FROM chapters WHERE course_id = c.id) as chapter_count,
                (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id) as student_count
            FROM courses c
            JOIN users u ON c.teacher_id = u.id
            WHERE c.id = ?";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$courseId]);
            $course = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($course) {
                $sql = "SELECT * FROM chapters WHERE course_id = ? ORDER BY id";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([$courseId]);
                $course['chapters'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            
            return $course;
        }

        public function getAllAvailable() {
            $sql = "SELECT 
                c.*,
                u.name as teacher_name,
                cat.name as category_name,
                (SELECT COUNT(*) FROM chapters WHERE course_id = c.id) as chapter_count,
                (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id) as student_count,
                CASE WHEN e.student_id IS NOT NULL THEN 1 ELSE 0 END as is_enrolled
            FROM courses c
            JOIN users u ON c.teacher_id = u.id
            LEFT JOIN categories cat ON c.category_id = cat.id
            LEFT JOIN enrollments e ON c.id = e.course_id AND e.student_id = ?
            ORDER BY c.created_at DESC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$_SESSION['user_id'] ?? null]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getCourseTags($courseId) {
            try {
                $sql = "SELECT tag_id FROM course_tags WHERE course_id = ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([$courseId]);
                
                // Return array of tag IDs
                return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'tag_id');
            } catch (PDOException $e) {
                error_log("Error getting course tags: " . $e->getMessage());
                return [];
            }
        }

        public function updateTags($courseId, $tagIds) {
            try {
                // Supprimer les anciens tags
                $stmt = $this->conn->prepare("DELETE FROM course_tags WHERE course_id = ?");
                $stmt->execute([$courseId]);

                //add new tags
                if (!empty($tagIds)) {
                    $stmt = $this->conn->prepare("
                        INSERT INTO course_tags (course_id, tag_id)
                        VALUES (?, ?)
                    ");

                    foreach ($tagIds as $tagId) {
                        $stmt->execute([$courseId, $tagId]);
                    }
                }

                return true;
            } catch (PDOException $e) {
                return false;
            }
        }

        public function getById($courseId) {
            try {
                $stmt = $this->conn->prepare("
                    SELECT c.*, u.name as teacher_name,
                           cat.name as category_name
                    FROM courses c
                    JOIN users u ON c.teacher_id = u.id
                    JOIN categories cat ON c.category_id = cat.id
                    WHERE c.id = ?
                ");
                
                $stmt->execute([$courseId]);
                $course = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($course) {
                    //get tags for the course
                    $stmt = $this->conn->prepare("
                        SELECT t.* 
                        FROM tags t
                        JOIN course_tags ct ON t.id = ct.tag_id
                        WHERE ct.course_id = ?
                    ");
                    $stmt->execute([$courseId]);
                    $course['tags'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                }

                return $course;
            } catch (PDOException $e) {
                return false;
            }
        }

        public function getTotalCourses() {
            try {
                // simplified query without status condition
                $sql = "SELECT COUNT(*) as total FROM courses";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result['total'];
            } catch (PDOException $e) {
                return 0;
            }
        }

        public function getLastCourses() {
            try {
                // simple query to debug
                $sql = "SELECT * FROM courses ORDER BY id DESC LIMIT 5";
                
                $stmt = $this->conn->prepare($sql);
                $stmt->execute();
                
                // Debug: Display the number of results
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                error_log("Number of courses found: " . count($results));
                
                if(empty($results)) {
                    error_log("No courses found in database");
                } else {
                    error_log("First course data: " . print_r($results[0], true));
                }
                
                return $results;
            } catch (PDOException $e) {
                error_log("Database error: " . $e->getMessage());
                return [];
            }
        }

        public function getTotalActiveCourses() {
            try {
                $sql = "SELECT COUNT(*) as total FROM courses WHERE status = 'active'";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result['total'];
            } catch (PDOException $e) {
                error_log("Error getting total active courses: " . $e->getMessage());
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
                    FROM courses";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return round($result['growth_rate'], 1);
            } catch (PDOException $e) {
                error_log("Error calculating course growth rate: " . $e->getMessage());
                return 0;
            }
        }

        public function getPopularCourses() {
            try {
                $sql = "SELECT c.*, 
                       cat.name as category_name,
                       u.name as teacher_name,
                       COUNT(DISTINCT e.student_id) as student_count
                FROM courses c
                LEFT JOIN categories cat ON c.category_id = cat.id
                LEFT JOIN users u ON c.teacher_id = u.id
                LEFT JOIN enrollments e ON c.id = e.course_id
                GROUP BY c.id
                ORDER BY student_count DESC";
                
                $stmt = $this->conn->prepare($sql);
                $stmt->execute();
                $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

                //get tags for each course
                foreach ($courses as &$course) {
                    $stmt = $this->conn->prepare("
                        SELECT t.* 
                        FROM tags t
                        JOIN course_tags ct ON t.id = ct.tag_id
                        WHERE ct.course_id = ?
                    ");
                    $stmt->execute([$course['id']]);
                    $course['tags'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                }

                return $courses;
            } catch (PDOException $e) {
                error_log("Error fetching popular courses: " . $e->getMessage());
                return [];
            }
        }

        public function searchCourses($search = '', $category = '', $tag = '') {
            $sql = "SELECT DISTINCT 
                    c.*, 
                    cat.name as category_name,
                    u.name as teacher_name,
                    u.profile_image as teacher_image,
                    COUNT(DISTINCT e2.student_id) as student_count,
                    CASE WHEN e1.student_id IS NOT NULL THEN 1 ELSE 0 END as is_enrolled
                    FROM courses c
                    LEFT JOIN categories cat ON c.category_id = cat.id
                    LEFT JOIN users u ON c.teacher_id = u.id
                    LEFT JOIN course_tags ct ON c.id = ct.course_id
                    LEFT JOIN tags t ON ct.tag_id = t.id
                    LEFT JOIN enrollments e1 ON c.id = e1.course_id AND e1.student_id = ?
                    LEFT JOIN enrollments e2 ON c.id = e2.course_id
                    WHERE 1=1";
            
            $params = [$_SESSION['user_id'] ?? null];  // Handle case where user is not logged in
            
            if (!empty($search)) {
                $sql .= " AND (c.title LIKE ? OR c.description LIKE ?)";
                $params[] = "%$search%";
                $params[] = "%$search%";
            }
            
            if (!empty($category)) {
                $sql .= " AND c.category_id = ?";
                $params[] = $category;
            }
            
            if (!empty($tag)) {
                $sql .= " AND t.id = ?";
                $params[] = $tag;
            }
            
            $sql .= " GROUP BY c.id ORDER BY student_count DESC";
            
            try {
                $stmt = $this->conn->prepare($sql);
                $stmt->execute($params);
                $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Get tags for each course (maintaining existing functionality)
                foreach ($courses as &$course) {
                    $stmt = $this->conn->prepare("
                        SELECT t.* 
                        FROM tags t
                        JOIN course_tags ct ON t.id = ct.tag_id
                        WHERE ct.course_id = ?
                    ");
                    $stmt->execute([$course['id']]);
                    $course['tags'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                }

                return $courses;
            } catch (PDOException $e) {
                error_log("Error in searchCourses: " . $e->getMessage());
                return [];
            }
        }

    }

?>