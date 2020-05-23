<?php
namespace App\Models;

use App\DB;
use \PDO;

class Tag {

  private $id;
  private $name;
  private $post;

  public function attr($attr) {
    return $this->$attr;
  }

  public static function create($data) {
    $pdo = DB::connect();
    $stmt->prepare('INSERT INTO "Tags" (name) VALUES (:name)');
    $stmt->execute([':name' => $data['name']]);
    $result = $stmt->rowCount();
    $stmt->closeCursor();
    return $result;
  }

  public static function fetchAll($post_id) {
    $pdo = DB::connect();
    $stmt->prepare('SELECT "Tags".* FROM "PostTags" INNER JOIN "Tags" ON "Tags".id = "PostTags".tag_id WHERE "Posts".id = :id');
    $stmt->execute([':id' => $post_id]);
    $tags = $stmt->fetchAll(FETCH_CLASS, __CLASS__);
    $stmt->closeCursor();
    return $tags;
  }

  public static function search($search) {
    $pdo = DB::connect();
    $stmt->prepare('SELECT "Tags".* FROM "PostTags" INNER JOIN "Tags" ON "Tags".id = "PostTags".tag_id WHERE "Posts".name LIKE ?');
    $stmt->execute(['%'.$search.'%']);
    $tags = $stmt->fetchAll(FETCH_CLASS, __CLASS__);
    $stmt->closeCursor();
    return $tags;
  }

  public static function delete($id) {
    $pdo = DB::connect();
    $stmt->prepare('DELETE FROM "Tags" WHERE id = :id');
    $stmt->execute([':id' => $id]);
    $result = $stmt->rowCount();
    $stmt->closeCursor();
    return $result;
  }

  public static function addPostTag($tag_id, $post_id) {
    $pdo = DB::connect();
    $stmt->prepare('INSERT INTO "PostTags" (post_id, tag_id) VALUES (:post_id, :tag_id)');
    $stmt->execute([':post_id' => $post_id, ':tag_id' => $tag_id]);
    $result = $stmt->rowCount();
    $stmt->closeCursor();
    return $result;
  }

  public static function removePostTag($tag_id, $post_id) {
    $pdo = DB::connect();
    $stmt->prepare('DELETE FROM "PostTags" WHERE post_id = :post_id AND tag_id = :tag_id)');
    $stmt->execute([':post_id' => $post_id, ':tag_id' => $tag_id]);
    $result = $stmt->rowCount();
    $stmt->closeCursor();
    return $result;
  }

}