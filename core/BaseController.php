<?php

class BaseController
{
    public function __construct() {
        // Empty constructor is fine
    }

    protected function renderDashboard($view, $data = [])
    {
        if (file_exists(__DIR__ . "/../app/views/{$view}.php")) {

            extract($data);
            
            
            require_once(__DIR__ . "/../app/views/{$view}.php");
        } else {
            throw new Exception("Vue non trouvée: {$view}");
        }
    }

    protected function render($view, $data = [])
    {
        if (file_exists(__DIR__ . "/../app/views/{$view}.php")) {
            extract($data);
            include_once __DIR__.'/../app/views/'.$view.'.php';
        }else{
            throw new Exception("Vue non trouvée: {$view}");
        }
    }

        public function renderAdmin($view, $data = []){
            
            extract($data);
            include_once __DIR__.'/../app/views/admin/'.$view.'.php';
        }
        
        public function renderTeacher($view, $data = []){
            
            extract($data);
            require_once __DIR__ . "/../app/views/teacher/{$view}.php";
        }

        public function renderStudent($view, $data = []){
            
            extract($data);
            include_once __DIR__.'/../app/views/student/'.$view.'.php';
        }
    }
