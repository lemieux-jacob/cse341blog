<?php
namespace App\Controllers;

use App\Models\User;
use Core\Auth;

class UserController {

  function __construct() {
    //
  }

  public function index() {
    $users = User::fetchAll();

    return render('users/index', ['users' => $users]);  
  }

  public function show() {
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT); 
    
    $user = User::fetch($id);

    return view('users/show', ['user' => $user]);
  }

  public function edit() {
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    $user = User::fetch($id);

    return view('users/edit', ['user' => $user]);
  }

  public function update() {
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

    $data = [
      'display_name' => filter_input(INPUT_POST, 'display_name', FILTER_SANITIZE_STRING),
      'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_NUMBER_INT),
      'is_admin' => filter_input(INPUT_POST, 'is_admin', FILTER_SANITIZE_NUMBER_INT),
    ];

    $user = User::fetch($id);

    return redirect('/users?user=' . $id, 'Account Updated!');
  }

}