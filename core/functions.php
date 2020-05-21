<?php
// Render Views and Partials (e.g. render('views/home', $data_array))
function render($name, $data = []) {
  extract($data);

  require "../app/views/{$name}.php";
  exit;
}

// Redirect with Optional Message
function redirect($location, $msg = "") {
  $_SESSION['msg'] = $msg;
  header('Location: ' . $location);
  exit;
}

/**
 * Pluralize Word
 * A stub for a more robust solution or dependancy.
 */
function strPlural($str) {
  return $str . "s";
}

// For Debugging
function dd($var) {
  var_dump($var);
  die();
}

// Check if User is Logged in

// Authorize User

// Protect Route
