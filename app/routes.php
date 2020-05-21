<?php
// Register Routes Here
if (!isset($router)) {
  dd('Error!: Router not instantiated');
}

$router->register('/', 'BlogController@index');

$router->register('blog', 'BlogController@index');

$router->register('posts', 'PostController@index');

$router->register('users', 'UserController@index');