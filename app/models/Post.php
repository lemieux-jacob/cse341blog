<?php
namespace App\Models;

use App\Models\User;
use App\Models\Tag;
use App\DB;
use \PDO;

class Post {

  public $id;
  public $user_id;
  public $title;
  public $body;
  public $posted_on;

  // Get Post Author
  public function author() {
    return User::fetch($this->user_id);
  }

  // Fetch all Tags for Post
  public function tags() {
    $pdo = DB::connect();
    $stmt = $pdo->prepare('SELECT "Tags".* FROM "PostTags" INNER JOIN "Tags" ON "Tags".id = "PostTags".tag_id WHERE "PostTags".post_id = :id');
    $stmt->execute([':id' => $this->id]);
    $tags = $stmt->fetchAll(PDO::FETCH_CLASS, 'App\Models\Tag');
    $stmt->closeCursor();
    return $tags;
  }

  // Cast Array to Post
  public static function cast($data) {
    $post = new Self();
    $post->id = $data['id'];
    $post->user_id = $data['user_id'];
    $post->title = $data['title'];
    $post->body = $data['body'];
    $post->posted_on = $data['posted_on'];
    return $post;
  }

  // Create a new Post in DB
  public static function create(Array $data) {
    $pdo = DB::connect();
    $stmt = $pdo->prepare('INSERT INTO "Posts" (user_id, title, body)
      VALUES (:user_id, :title, :body)');
    $stmt->execute([
      ':user_id' => $data['user_id'],
      ':title' => $data['title'],
      ':body' => $data['body']
    ]);
    $row = $stmt->rowCount();
    $stmt->closeCursor();
    return $row;
  }

  // Fetch a Post from the DB
  public static function fetch(int $id) {
    $pdo = DB::connect();
    $stmt = $pdo->prepare('SELECT * FROM "Posts" WHERE "Posts".id = :id');
    $stmt->execute([':id' => $id]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $posts = [];
    foreach ($rows as $post) {
      $posts[] = Post::cast($post);
    }
    $stmt->closeCursor();
    return $posts[0];
  }

  // Fetch all Posts from the DB
  public static function fetchAll($search = null) {
    $pdo = DB::connect();
    if ($search != null) {
      $stmt = $pdo->prepare('SELECT * FROM "Posts" WHERE title LIKE ? ORDER BY posted_on DESC');
      $stmt->execute(['%'.$search.'%']);
    } else {
      $stmt = $pdo->prepare('SELECT * FROM "Posts" ORDER BY posted_on DESC');
      $stmt->execute();
    }
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $posts = [];
    foreach ($rows as $post) {
      $posts[] = Post::cast($post);
    }
    $stmt->closeCursor();
    return $posts;
  }

  // Update Post in DB
  public function update($data) { 
    $pdo = DB::connect();
    $stmt = $pdo->prepare('UPDATE "Posts" 
      SET user_id = :user_id, title = :title, body = :body
      WHERE id = :id');
    $stmt->execute([
      ':id' => $this->id,
      ':user_id' => isset($data['user_id']) ? $data['user_id'] : $this->user_id,
      ':title' => isset($data['title']) ? $data['title'] : $this->title,
      ':body' => isset($data['body']) ? $data['body'] : $this->body
    ]);
    $result = $stmt->rowCount();
    $stmt->closeCursor();
    return $result;
  }

  // Delete Post from DB
  public static function delete($id) {
    $pdo = DB::connect();
    $stmt = $pdo->prepare('DELETE FROM "Posts" WHERE id=:id');
    $stmt->execute([':id' => $id]);
    $result = $stmt->rowCount();
    $stmt->closeCursor();
    return $result;
  }

  // Add Tag
  public function addTag($tag_id) {
    $pdo = DB::connect();
    $stmt = $pdo->prepare('INSERT INTO "PostTags" (post_id, tag_id) VALUES (:post_id, :tag_id)');
    $stmt->execute([':post_id' => $this->id, ':tag_id' => $tag_id]);
    $result = $stmt->rowCount();
    $stmt->closeCursor();
    return $result;
  }

  // Remove Tag
  public function removeTag($tag_id, $post_id) {
    $pdo = DB::connect();
    $stmt = $pdo->prepare('DELETE FROM "PostTags" WHERE post_id = :post_id AND tag_id = :tag_id)');
    $stmt->execute([':post_id' => $this->id, ':tag_id' => $tag_id]);
    $result = $stmt->rowCount();
    $stmt->closeCursor();
    return $result;
  }
}