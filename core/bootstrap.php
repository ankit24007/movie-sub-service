<?php

use Core\{Router, Request};

// load ENV file
Dotenv\Dotenv::createImmutable(dirname(__DIR__, 1))->load();

if($_ENV['DEBUG']){
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

$routes = dirname(__DIR__, 1).'/routes.php';

Router::load($routes)
    ->direct(
        Request::uri(), 
        Request::method()
    );