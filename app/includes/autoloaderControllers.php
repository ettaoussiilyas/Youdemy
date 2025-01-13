<?php

spl_autoload_register(function($class){
    // Check f dossier models
    $modelPath = __DIR__.'/../models/'.$class.'.php';
    
    // Check f dossier helpers
    $helperPath = __DIR__.'/../helpers/'.$class.'.php';
    
    if(file_exists($modelPath)){
        require_once $modelPath;
    } elseif(file_exists($helperPath)){
        require_once $helperPath;
    }
});

?>