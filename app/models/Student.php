<?php

require_once __DIR__ . '/../config/db.php';

class Student extends Db {

    public function __construct() {
        parent::__construct();
    }

    public function getTotalStudents() {
        try {
            $sql = "SELECT COUNT(*) as total FROM users WHERE role = 'student'";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            return 0;
        }
    }
}

?>
