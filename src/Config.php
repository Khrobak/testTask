<?php 
    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 1);
    
    session_start();
    
    define('DB_HOST', 'localhost');
    define('DB_USER', 'user');
    define('DB_PASSWORD', 'password');
    define('DB_NAME', 'testtask');
    $path = ini_get('include_path').';'.'src';
    //test
    set_include_path($path);
    spl_autoload_register();
?>