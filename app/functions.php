<?php
// Get Current User as User Object
function user() {
  if (isset($_SESSION['user'])) {
    $user = App\Models\User::cast($_SESSION['user']);
    return $user;
  }
  return false;
}

// Authorize User for Resource
function access($resource) {
  if (!user()) {
    return false;
  } else if (user()->id === $resource->user_id) {
    return 'owner';
  } else if (user()->isAdmin()) {
    return 'admin';
  }
  return false;
}

function auth($resource = null) {
  if (access($resource) != false) {
    return true;
  }
}

// Render Views and Partials (e.g. render('views/home', $data_array))

// Get Page
function view($name, $data = []) {
  extract($data);

  return require "../app/views/{$name}.view.php";
}

// Get Partial
function partial($name, $data = []) {
  extract($data);

  return require "../app/partials/{$name}.php";
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

// Error
function error($msg) {
  $classes = [
    'Error!' => 'alert alert-danger text-danger',
    'Notice!' => 'alert alert-warning text-warning',
    'Info!' => 'alert alert-info text-info',
    'Success!' => 'alert alert-success text-success'
  ];
  $parts = explode(':', $msg);
  view('error', ['msg' => "<div class='{$classes[$parts[0]]}'>$msg</div>"]);
  exit;
}

// For Debugging
function dd($var) {
  var_dump($var);
  die();
}
