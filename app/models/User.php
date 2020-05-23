<?php
namespace App\Models;

use App\DB;
use \PDO;

class User {

  public $id;
  public $display_name;
  private $email;
  private $password;
  private $is_admin;

  // Get User Attributes
  public function attr($attr) {
    return $this->$attr;
  }

  // Check if User is Admin
  public function isAdmin() {
    return $this->is_admin;
  }

  public static function cast($data) {
    $user = new Self();
    $user->id = $data['id'];
    $user->display_name = $data['display_name'];
    $user->email = $data['email'];
    $user->password = $data['password'];
    $user->is_admin = $data['is_admin'];
    return $user;
  }

  public function toArray() {
    return [
      'id' => $this->id,
      'display_name' => $this->display_name,
      'email' => $this->email,
      'password' => $this->password,
      'is_admin' => $this->is_admin
    ];
  }

  public function password($password) {
    return password_verify($password, $this->password);
  }

  // Create a new User in DB
  public static function create(Array $data) {
    $pdo = DB::connect();
    $stmt = $pdo->prepare('INSERT INTO "Users" (display_name, email, password, is_admin)
      VALUES (:display_name, :email, :password, :is_admin)');
    $stmt->execute([
      ':display_name' => $data['display_name'],
      ':email' => $data['email'],
      ':password' => $data['password'],
      ':is_admin' => $data['is_admin']
    ]);
    $result = $stmt->rowCount();
    $stmt->closeCursor();
    return $result;
  }

  // Fetch a User from the DB
  public static function fetch(int $id) {
    $pdo = DB::connect();
    $stmt = $pdo->prepare('SELECT * FROM "Users" WHERE id=:id');
    $stmt->execute([':id' => $id]);
    $rows = $stmt->fetchAll(PDO::FETCH_CLASS, __CLASS__);
    $user = $rows[0];
    $stmt->closeCursor();
    return $user;
  }

  // Fetch all Users from the DB
  public static function fetchAll() {
    $pdo = DB::connect();
    $pdo = $this->db->dbh;
    $stmt = $pdo->prepare('SELECT * FROM "Users"');
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_CLASS, __CLASS__);
    $stmt->closeCursor();
    return $users;
  }

  // Update User in DB
  public function update($data) { 
    $pdo = DB::connect();
    $stmt = $pdo->prepare('UPDATE "Users" 
      SET display_name = :display_name, is_admin = :is_admin, password = :password
      WHERE id = :id');
    $stmt->execute([
      ':id' => isset($data['id']) ? $data['id'] : $this->id,
      ':display_name' => isset($data['display_name']) ? $data['display_name'] : $this->display_name,
      // ':email' => isset($data['email']) ? $data['email'] : $this->email,
      ':password' => isset($data['password']) ? $data['password'] : $this->password,
      ':is_admin' => isset($data['is_admin']) ? $data['is_admin'] : $this->is_admin
    ]);
    $result = $stmt->rowCount();
    $stmt->closeCursor();
    return $result;
  }

  // Delete User from DB
  public static function delete($id) {
    $pdo = DB::connect();
    $stmt = $pdo->prepare('DELETE FROM "Users" WHERE id=:id');
    $stmt->execute([':id' => $id]);
    $result = $stmt->rowCount();
    $stmt->closeCursor();
    return $result;
  }

  // Get User by Email
  public static function withEmail($email) {
    $db = DB::connect();
    $sql = 'SELECT * FROM "Users" WHERE email = :email';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_CLASS, __CLASS__);
    $user = $rows[0]; 
    $stmt->closeCursor();
    return $user;
  }

  // Email is Unique in DB
  public static function hasUniqueEmail($email) {
    $db = DB::connect();
    $sql = 'SELECT email FROM "Users" WHERE email = :email';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $matchEmail = $stmt->fetch(PDO::FETCH_NUM);
    $stmt->closeCursor();

    if (empty($matchEmail)) {
      return true;
    } else {
      return false;
    }
  }
}