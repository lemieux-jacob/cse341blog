<?php
namespace App\Controllers;

use App\Models\User;

class UserController {

  /**
   * View All Users (Not Implemented)
  */
  public function index() {
    if (!user()->isAdmin()) {
      error('Error!: Action is not Authorized');
    }

    $users = User::fetchAll();

    return view('users/index', ['users' => $users]);
  }

  /**
   * Display a User's Dashboard
  */
  public function show() {
    if (!user()) {
      error('Error!: Action is not Authorized');
    }
    return view('users/dashboard', ['user' => User::fetch(user()->id)]);
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
    $data['is_admin'] = "false";

    // Hash the Password
    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

    // Create the new User and store it in the DB
    $result = User::create($data);

    if ($result) {
      return redirect('/login' . $user->id, 'Success!: Account Created! Please Login!');
    }

    return redirect('/' . $user->id, 'Error!: Account Creation failed! Please try again!');
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
      return view('users/login', ['msg' => 'Notice!: Please enter a valid email and password!']);
    }

    $user = User::withEmail($data['email']);

    if (empty($user) || $user->password($data['password']) != $data['password']) {
      return view('users/login', ['msg' => 'Notice!: Incorrect email or password combination!']);
    }

    $_SESSION['user'] = $user->toArray();

    return redirect('/', 'Success!: You are logged in as ' . $user->display_name);
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
    $form = $this->validateUser([
      'id' => filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT),
      'display_name' => filter_input(INPUT_POST, 'display_name', FILTER_SANITIZE_STRING),
    ], 'update');

    if (!$form['is_valid']) {
      return view('users/edit', [
        'msg' => $form['error'],
        'form' => $form,
        'user' => isset($form['id']) ? User::fetch($form['id']) : user()
      ]);
    }

    if (!user()->isAdmin() && user()->id != $form['id']) {
      error('Error!: Action is not authorized');
    }

    $user = User::fetch($form['id']);

    if (!$user) {
      error('Error!: Record does not exist for User');
    }

    $result = $user->update($form);

    if ($result) {
      return redirect('/dashboard', 'Success!: Account Updated!');
    }
    return redirect('/dashboard', 'Error!: Account Update failed!');
  }

  public function changePassword() {
    $form = $this->validateUser([
      'id' => filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING),
      'old_password' => filter_input(INPUT_POST, 'old_password'),
      'password' => filter_input(INPUT_POST, 'password'),
      'confirm_password' => filter_input(INPUT_POST, 'confirm_password')
    ], 'update', ['validate-password' => true]);

    if (!$form['is_valid']) {
      return view('users/edit', [
        'msg' => $form['error'],
        'form' => $form,
        'user' => isset($form['id']) ? User::fetch($form['id']) : user()
      ]);
    }

    if (!user()->isAdmin() && user()->id != $form['id']) {
      error('Error!: Action is not authorized');
    }

    $user = User::fetch($form['id']);

    if (!$user->password($form['old_password'])) {
      return view('/users/edit', [
        'msg' => 'Notice!: Current Password does not match our records',
        'form' => $form,
        'user' => isset($form['id']) ? User::fetch($form['id']) : user()
      ]);
    }

    // Hash the Password
    $form['password'] = password_hash($form['password'], PASSWORD_DEFAULT);

    if (!$user) {
      error('Error!: Record does not exist for User');
    }

    $result = $user->update($form);

    if ($result) {
      // Log the User out
      session_destroy();

      // Start new Session
      session_start();

      return redirect('/login', 'Success!: Password Changed! Please login again with your new password.');
    }
    return redirect('/dashboard', 'Error!: Account Update failed!');
  }

  /**
   * Delete User 
   */
  public function delete() {
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

  }

  /**
   * Validate User Form Data
  */
  protected function validateUser($form, $action, $options = []) {
    // Ensure all required fields are populated for action
    foreach($form as $field) {
      if (empty($field)) {
        $form['is_valid'] = false;
        $form['error'] = 'Notice!: A required field is missing or invalid!';
        return $form;
      }
    }
    if (isset($options['validate-password']) && $options['validate-password'] === true) {
      if (!$form['password'] === $form['confirm_password']) {
        $form['is_valid'] = false;
        $form['error'] = 'Notice!: Passwords do not match!';
        return $form;
      }
      if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/", $form['password'])) {
        // Minimum eight characters, at least one letter, one number and one special character.
        $form['is_valid'] = false;
        $form['error'] = 'Notice!: Please enter a valid password.';
        return $form;
      }
    }
    // Verify Email is Unique
    if ($action === 'create') {
      if (!User::hasUniqueEmail($form['email'])) {
        $form['is_valid'] = false;
        $form['error'] = 'Notice!: An Account already exists for that Email';
        return $form;
      }
    }
    $form['is_valid'] = true;
    return $form;
  }
}