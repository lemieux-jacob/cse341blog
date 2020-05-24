<?php
namespace App\Models;

use App\DB;
use \PDO;

class Tag {

  private $id;
  private $name;

  public function attr($attr) {
    return $this->$attr;
  }

  // References Posts via PostTags Table
  public function posts() {
    $pdo = DB::connect();
    $stmt = $pdo->prepare('SELECT "Posts".* FROM "PostTags" INNER JOIN "Posts" ON "Posts".id = "PostTags".post_id WHERE "PostTags".tag_id = :id');
    $stmt->execute([':id' => $this->id]);
    $posts = $stmt->fetchAll(PDO::FETCH_CLASS, "App\Models\Post");
    $stmt->closeCursor();
    return $posts;
  }

  public static function create($data) {
    $pdo = DB::connect();
    $stmt = $pdo->prepare('INSERT INTO "Tags" (name) VALUES (:name) RETURNING *');
    $stmt->execute([':name' => $data['name']]);
    $rows = $stmt->fetchAll(PDO::FETCH_CLASS, __CLASS__);
    $stmt->closeCursor();
    return $rows[0];
  }

  public static function fetch($name) {
    $pdo = DB::connect();
    $stmt = $pdo->prepare('SELECT * FROM "Tags" WHERE name = :name');
    $stmt->execute([':name' => $name]);
    $tags = $stmt->fetchAll(PDO::FETCH_CLASS, __CLASS__);
    $stmt->closeCursor();
    if (count($tags) > 0) {
      return $tags[0];
    } else {
      return false;
    }
  }

  public static function delete($id) {
    $pdo = DB::connect();
    $stmt = $pdo->prepare('DELETE FROM "Tags" WHERE id = :id');
    $stmt->execute([':id' => $id]);
    $result = $stmt->rowCount();
    $stmt->closeCursor();
    return $result;
  }

}