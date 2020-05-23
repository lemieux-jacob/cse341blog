<?php
// Get Current User as User Object
function user() {
  if (isset($_SESSION['user'])) {
    $user = App\Models\User::cast($_SESSION['user']);
    return $user;
  }
  return false;
}

function auth($access = 'user') {
  if (user()) {
    if ($access === 'user') {
      return true;
    }
    if ($user->isAdmin()) {
      return true;
    }
  } else if ($access === 'guest') {
    return true;
  }
  return false;
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

// For Debugging
function dd($var) {
  var_dump($var);
  die();
}
