<?php
use Core\Router;

// Start or Resume Session
session_start();

// Require Composer Autoload
require '../vendor/autoload.php';

$uri = $_SERVER['REQUEST_URI'];

// Instantiate Router
$router = new Router();

// Require Routes File
require '../app/routes.php';

$router->direct($uri);