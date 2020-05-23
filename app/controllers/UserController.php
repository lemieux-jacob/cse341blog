<?php
namespace App\Controllers;

use App\Models\User;

class UserController {

  /**
   * View All Users (Not Implemented)
  */
  public function index() {
    if (!user()->isAdmin()) {
      dd('Notice!: Action is not Authorized');
    }

    $users = User::fetchAll();

    return view('users/index', ['users' => $users]);  
  }

  /**
   * Display a User's Account Info
  */
  public function show() {
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    $user = User::fetch($id);

    if (!user()->isAdmin() && user()->id != $user->id) {
      dd('Notice!: Action is not Authorized');
    }

    return view('users/show', ['user' => $user]);
  }

  /**
   * Register a new User :: Form
  */
  public function create() {
    // Redirect if User is Logged in
    if (user()) {
      return redirect('/');
    }

    return view('users/create');
  }

  /*
  * Register a new User :: Action
  */
  public function store() {
    if (user()) {
      return redirect('/');
    }
    // Validate Form Data
    $data = $this->validateUser([
      'display_name' => filter_input(INPUT_POST, 'display_name', FILTER_SANITIZE_STRING),
      'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
      'password' => filter_input(INPUT_POST, 'password'),
      'confirm_password' => filter_input(INPUT_POST, 'confirm_password'),
      // 'is_admin' => filter_input(INPUT_POST, 'is_admin', FILTER_SANITIZE_NUMBER_INT),
    ], 'create');

    if (!$data['is_valid']) {
      return view('users/create', [
        'msg' => $data['error'],
        'form' => $data
      ]);
    }

    // Initialize the Is Admin field to default (False)
    $data['is_admin'] = false;

    // Hash the Password
    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

    // Create the new User and store it in the DB
    $result = User::create($data);

    if ($result) {
      return redirect('/login' . $user->id, 'Account Created! Please Login!');
    }

    return redirect('/' . $user->id, 'Account Creation failed! Please try again!');
  }

  /**
   * Login User :: Form
   */
  public function loginForm() {
    if (user()) {
      return redirect('/');
    }
    return view('users/login');
  }

  /**
   * Login User :: Action
   */
  public function loginAction() {
    $data = [
      'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
      'password' => filter_input(INPUT_POST, 'password')
    ];

    if (empty($data['email']) || empty($data['password'])) {
      return view('users/login', ['msg' => 'Please enter a valid email and password!']);
    }

    $user = User::withEmail($data['email']);

    if (empty($user) || $user->password($data['password']) != $data['password']) {
      return view('users/login', ['msg' => 'Incorrect email or password combination!']);
    }

    $_SESSION['user'] = $user->toArray();

    return redirect('/', 'You are logged in as ' . $user->display_name);
  }

  /**
   * Logout User :: Action
   */
  public function logout() {
    session_destroy();
    return redirect('/', 'Logged out!');
  }

  /**
   * Update an Existing User :: Form
  */
  public function edit() {
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    if (!empty($id)) {
      $user = User::fetch($id);
    } else {
      $user = User::fetch(user()->id);
    }

    return view('users/edit', ['user' => $user]);
  }

  /* 
   * Update an Existing User :: Action
   */
  public function update() {
    // Validate Form Data
    $data = $this->validateUser([
      'id' => filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT),
      'display_name' => filter_input(INPUT_POST, 'display_name', FILTER_SANITIZE_STRING),
      // 'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
      'password' => filter_input(INPUT_POST, 'password'),
      'confirm_password' => filter_input(INPUT_POST, 'confirm_password')
    ], 'update');

    if (!$data['is_valid']) {
      return view('users/create', [
        'msg' => $data['error'],
        'user' => isset($data['id']) ? User::fetch($data['id']) : user()
      ]);
    }

    if (user()->isAdmin()) {
      $data['is_admin'] = filter_input(INPUT_POST, 'is_admin', FILTER_VALIDATE_BOOLEAN);
    }

    // Hash the Password
    if (!empty($data['password'])) {
      $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
    }

    $user = User::fetch($data['id']);

    if (!$user) {
      dd('Record does not exist for User.');
    }

    $result = $user->update($data);

    if ($result) {
      return redirect('/users?user=' . $user->id, 'Account Updated!');
    }
    return redirect('/users?user=' . $user->id, 'Account Update failed!');
  }

  /**
   * Validate User Form Data
  */
  protected function validateUser($form, $action) {
    foreach($form as $field) {
      if (empty($field)) {
        $form['is_valid'] = false;
        $form['error'] = 'A required field is missing or invalid!';
        return $form;
      }
    }
    if (!$form['password'] === $form['confirm_password']) {
      $form['is_valid'] = false;
      $form['error'] = 'Passwords do not match!';
      return $form;
    }
    if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/", $form['password'])) {
      /**
       * Minimum eight characters, at least one letter, one number and one special character.
      */
      $form['is_valid'] = false;
      $form['error'] = 'Please enter a valid password.';
      return $form;
    }
    if ($action === 'create') {
      if (!User::hasUniqueEmail($form['email'])) {
        $form['is_valid'] = false;
        $form['error'] = 'An Account already exists for that Email!';
        return $form;
      }
    }
    $form['is_valid'] = true;
    return $form;
  }
}