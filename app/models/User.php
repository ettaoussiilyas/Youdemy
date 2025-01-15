<?php


    class User extends Db{

        public function __construct(){
            parent::__construct();
        }

        public function getUserByEmail($email){
            $sql = "SELECT * FROM users WHERE email = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$email]);
            return $stmt->fetch();
        }

        public function getStatus($id){
            $sql = "SELECT status FROM users WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch();
        }

        public function createUser($name, $email, $password, $role){
            $sql = "INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            if($role === 'teacher'){
                return $stmt->execute([$name, $email, $password, $role, 'review']);
            }else{
                return $stmt->execute([$name, $email, $password, $role, 'active']);
            }
        }

        public function getById($userId) {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$userId]);  
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }