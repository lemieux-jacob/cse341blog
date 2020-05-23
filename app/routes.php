<?php
return [
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
?>