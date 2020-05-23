<?php
namespace App\Controllers;

use App\Models\Post;
use App\Models\Comment;

class PostController {

  /**
   * Display and Search All Posts
   */
  public function index() {
    $search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING);

    if (!empty($search)) {
      $search = ucwords(strtolower($search));
      $posts = Post::fetchAll($search);
    } else {
      $posts = Post::fetchAll();
    }

    return view('blog', ['posts' => $posts]);
  }

  /**
   * Display a Post
  */
  public function show() {
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    return view('posts/show', [
      'post' => Post::fetch($id), 
      'comments' => Comment::fetchAll($id)
    ]);
  }

  /**
   * Create a new Post :: Form
  */
  public function create() {
    $user = user();

    if (!$user->isAdmin()) {
      dd('Notice!: This Action is unauthorized!');
    }

    return view('posts/create', [
      'user' => $user,
      'form' => []
    ]);
  }

  /**
   * Create a new Post :: Action
  */
  public function store() {
    // Get Current User
    $user = user();

    if (!$user->isAdmin()) {
      dd('Notice!: This Action is unauthorized!');
    }

    // Collect Form Data
    $form = $this->validatePost([
      'user_id' => filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT),
      'title' => filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING),
      'body' => filter_input(INPUT_POST, 'body', FILTER_SANITIZE_STRING)
    ]);

    if (!$form['is_valid']) {
      return view('posts/create', [
        'user' => $user,
        'form' => $form, 
        'msg' => 'Some required fields were empty or invalid.'
      ]);
    }

    // Optimize Titles for Search
    $form['title'] = ucwords(strtolower($form['title']));

    $post = Post::create($form);

    if ($post) {
      return redirect('/posts', 'Post created!');
    }
    
    return redirect('/posts', 'Create Post failed!');
  }

  /**
   * Edit an existing Post :: Form
   */
  public function edit() {
    $user = user();

    if (!$user->isAdmin()) {
      dd('Notice!: This Action is unauthorized!');
    }

    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    return view('/posts/edit', [
      'user' => $user,
      'form' => [],
      'post' => Post::fetch($id)
    ]);
  }

  /**
   * Edit an existing Post :: Action
   */
  public function update() {
    $user = user();

    if (!$user->isAdmin()) {
      dd('Error!: This Action is unauthorized!');
    }

    // Collect Form Data
    $form = $this->validatePost([
      'post_id' => filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT),
      'user_id' => filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT),
      'title' => filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING),
      'body' => filter_input(INPUT_POST, 'body', FILTER_SANITIZE_STRING)
    ]);

    // Ensure Post Exists
    $post = Post::fetch($form['post_id']);

    if (!$post) {
      dd('Error!: Post does not exist!');
    }

    if (!$form['is_valid']) {
      return view('posts/create', [
        'user' => $user,
        'form' => $form,
        'post' => $post,
        'msg' => 'Some required fields were empty or invalid.'
      ]);
    }

    // Optimize Titles for Search
    $form['title'] = ucwords(strtolower($form['title']));

    $result = $post->update($form);

    if ($result) {
      return redirect('/', 'Post Updated!');
    }

    return redirect('/', 'Failed to Update Post!');
  }

  protected function validatePost($form) {
    // Ensure fields are not Empty
    foreach($form as $field) {
      if (empty($field)) {
        $form['is_valid'] = false;
        return $form;
      }
    }
    $form['is_valid'] = true;
    return $form;
  }

}