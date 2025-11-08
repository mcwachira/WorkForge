<?php

session_start();
require __DIR__ . "/../vendor/autoload.php";


use Framework\Router;
use Framework\Session;

Session::start();
require '../helpers.php';

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