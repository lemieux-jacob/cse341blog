<?php
/**
 * Bootstrap the Application
*/

use App\Router;

// Set up Error Reporting
error_reporting(E_ALL);

// Start or Resume Session
session_start();

// Require Composer's Autoload
require '../vendor/autoload.php';

/** 
 * Simple Router
*/

// Parse URI
$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

// Ensure URI is not Empty
$action = !empty($uri) ? $uri : '/';

// Ghetto Router
$routes = require '../app/routes.php';

// Instantiate Router
$router = new Router($routes);

// Direct to Controller
$router->direct($action);