<?php

require '../helpers.php';


spl_autoload_register(function ($class){
    require basePath('Framework/' . $class . '.php');
    if(file_exists($path)){
        require $path;
    }
});

// Instantiate the router
$router = new Router();

//Get routes
$routes = require basePath('routes.php');

//Get current URI nad HTTP method
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
//inspectAndDie($uri);
$method = $_SERVER['REQUEST_METHOD'];


//Route the request
$router->route($uri, $method);