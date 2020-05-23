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
  'posts/delete' => 'PostController@delete',
  // Comment Routes
  'comments/store' => 'CommentController@store',
  'comments/update' => 'CommentController@update',
  'comments/delete' => 'CommentController@delete',
  // Tag Routes
  'tags/add' => 'TagController@add',
  'tags/remove' => 'TagController@remove',
  // User Routes
  'register' => 'UserController@create',
  'users/store' => 'UserController@store',
  'login' => 'UserController@loginForm',
  'handle-login' => 'UserController@loginAction',
  'logout' => 'UserController@logout'
];
?>