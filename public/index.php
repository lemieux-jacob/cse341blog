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
$routes = [
  '/' => 'PostController@index',
  // Post Routes
  'posts' => 'PostController@index',
  'posts/create' => 'PostController@create',
  'posts/store' => 'PostController@store',
  'posts/view' => 'PostController@show',
  'posts/edit' => 'PostController@edit',
  'posts/update' => 'PostController@update',
  // Comment Routes
  'comments/store' => 'CommentController@store',
  'comments/update' => 'CommentController@update',
  'comments/delete' => 'CommentController@delete',
  // User Routes
  'register' => 'UserController@create',
  'users/store' => 'UserController@store',
  'login' => 'UserController@loginForm',
  'handle-login' => 'UserController@loginAction',
  'logout' => 'UserController@logout'
];

// Instantiate Router
$router = new Router($routes);

// Direct to Controller
$router->direct($action);