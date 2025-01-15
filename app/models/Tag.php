<?php
require_once __DIR__ . '/../config/db.php';

class Tag extends Db {
    private $db;

    public function __construct() {
        parent::__construct();
    }

    public function getAll() {
        $stmt = $this->conn->query("SELECT * FROM tags ORDER BY name");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} 