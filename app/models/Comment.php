<?php
namespace App\Models;

use App\DB;
use \PDO;

class Comment {

  public $id;
  public $user_id;
  public $post_id;
  public $body;
  public $posted_on;

  public function author() {
    return User::fetch($this->user_id);
  }

  // Cast Array to Comment
  public static function cast(Array $data) {
    $comment = new Self();
    $comment->id = $data['id'];
    $comment->user_id = $data['user_id'];
    $comment->post_id = $data['post_id'];
    $comment->body = $data['body'];
    $comment->posted_on = $data['posted_on'];
    return $comment;
  }

  // Create a new Comment in DB
  public static function create(Array $data) {
    $pdo = DB::connect();
    $stmt = $pdo->prepare('INSERT INTO "Comments" (user_id, post_id, body)
      VALUES (:user_id, :post_id, :body)');
    $stmt->execute([
      ':user_id' => $data['user_id'],
      ':post_id' => $data['post_id'],
      ':body' => $data['body']
    ]);
    $result = $stmt->rowCount();
    $stmt->closeCursor();
    return $result;
  }

  // Fetch Comments from the DB
  public static function fetch($id) {
    $pdo = DB::connect();
    $stmt = $pdo->prepare('SELECT * FROM "Comments" WHERE id=:id');
    $stmt->execute([':id' => $id]);
    $comments = $stmt->fetchAll(PDO::FETCH_CLASS, __CLASS__);
    $stmt->closeCursor();
    return $comments[0];
  }

  // Fetch all Comments for Post from the DB
  public static function fetchAll($post_id) {
    $pdo = DB::connect(); 
    $stmt = $pdo->prepare('SELECT * FROM "Comments" WHERE "Comments".post_id=:id');
    $stmt->execute([':id' => $post_id]);
    $comments = $stmt->fetchAll(PDO::FETCH_CLASS, __CLASS__);
    $stmt->closeCursor();
    return $comments;
  }

  // Update Comment in DB
  public function update(Array $data) { 
    $pdo = DB::connect();
    $stmt = $pdo->prepare('UPDATE "Comments" SET body = :body WHERE id = :id');
    $stmt->execute([
      ':id' => isset($data['id']) ? $data['id'] : $this->id,
      ':body' => isset($data['body']) ? $data['body'] : $this->body
    ]);
    $result = $stmt->rowCount();
    $stmt->closeCursor();
    return $result;
  }

  // Delete Comment from DB
  public static function delete($id) {
    $pdo = DB::connect();
    $stmt = $pdo->prepare('DELETE FROM "Comments" WHERE id=:id');
    $stmt->execute([':id' => $id]);
    $result = $stmt->rowCount();
    $stmt->closeCursor();
    return $result;
  }
}