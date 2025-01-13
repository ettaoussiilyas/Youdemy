<?php 

class Db {

    protected $conn ;

    public function __construct()
    {
        try {
            $this->conn = new PDO("mysql:host=localhost;dbname=youdemy", "root", "");
            
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            
          
          } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
          }
    }

}