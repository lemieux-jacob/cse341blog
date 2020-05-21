<?php
namespace App\Models;

use Core\DB;
use \PDO;

class User {

  private $id;
  private $display_name;
  private $email;
  private $password;
  private $is_admin = 0;

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
    $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
    $user = $stmt->fetch();
    $stmt->closeCursor();
    return $user;
  }

  // Fetch all Users from the DB
  public static function fetchAll() {
    $pdo = DB::connect();
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
      SET display_name = :display_name, email = :email, is_admin = :is_admin, password = :password
      WHERE id = :id');
    $stmt->execute([
      ':id' => $this->id,
      ':display_name' => isset($data['display_name']) ? $data['display_name'] : $this->display_name,
      ':email' => isset($data['email']) ? $data['email'] : $this->email,
      ':password' => isset($data['password']) ? $data['password'] : $this->password,
      ':is_admin' => isset($data['is_admin']) ? $data['is_admin'] : $this->is_admin
    ]);
    $result = $stmt->rowCount();
    $stmt->closeCursor();
    return $result;
  }

  public static function delete($id) {
    $pdo = DB::connect();
    $stmt = $pdo->prepare('DELETE FROM "Users" WHERE id=:id');
    $stmt->execute([':id' => $id]);
    $result = $stmt->rowCount();
    $stmt->closeCursor();
    return $result;
  }
}