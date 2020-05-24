<?php
namespace App\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Tag;

use Parsedown;

class PostController {

  private $pd;

  function __construct() {
    // Instantiate Parsedown
    $pd = new Parsedown();
    $pd->setSafeMode(true);
    $this->pd = $pd;
  }

  /**
   * Display and Search All Posts
   */
  public function index() {
    $searchQuery = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING);
    $tagname = filter_input(INPUT_GET, 'tag', FILTER_SANITIZE_STRING);

    if (!empty($searchQuery)) {
      // Find Posts by Title
      // Match Title Formating
      $searchQuery = ucwords(strtolower($searchQuery));
      $posts = Post::fetchAll($searchQuery);
    } else if (!empty($tagname)) {
      // Find Posts by Tag
      $tag = Tag::fetch($tagname);
      if (!$tag) {
        $posts = Post::fetchAll(); 
      } else {
        $posts = $tag->posts();
      }
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

    $post = Post::fetch($id);

    return view('posts/show', [
      'post' => $post,
      'tags' => $post->tags(),
      'comments' => Comment::fetchAll($id)
    ]);
  }

  /**
   * Create a new Post :: Form
  */
  public function create() {
    $user = user();

    if (!$user || !$user->isAdmin()) {
      error('Error!: This Action is unauthorized!');
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

    if (!$user || !$user->isAdmin()) {
      error('Error!: This Action is unauthorized!');
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
        'msg' => 'Notice!: Some required fields were empty or invalid.'
      ]);
    }

    // Optimize Titles for Search
    $form['title'] = ucwords(strtolower($form['title']));

    // Parse Body (Parsedown/Markdown)
    $form['body'] = $this->pd->text($form['body']);

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

    if (!$user || !$user->isAdmin()) {
      error('Error!: This Action is unauthorized!');
    }

    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    if (empty($id)) {
      error('Error!: Post does not exist');
    }

    $post = Post::fetch($id);

    return view('/posts/edit', [
      'user' => $user,
      'form' => [],
      'post' => $post,
      'tags' => $post->tags()
    ]);
  }

  /**
   * Edit an existing Post :: Action
   */
  public function update() {
    $user = user();

    if (!$user || !$user->isAdmin()) {
      error('Error!: This Action is unauthorized!');
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
      error('Error!: Post does not exist!');
    }

    if (!$form['is_valid']) {
      return view('posts/create', [
        'user' => $user,
        'form' => $form,
        'post' => $post,
        'msg' => 'Notice!: Some required fields were empty or invalid.'
      ]);
    }

    // Optimize Titles for Search
    $form['title'] = ucwords(strtolower($form['title']));

    // Parse Body (Parsedown/Markdown)
    $form['body'] = $this->pd->text($form['body']);

    $result = $post->update($form);

    if ($result) {
      return redirect('/', 'Success!: Post Updated');
    }

    return redirect('/', 'Error!: Failed to Update Post');
  }

  /**
   * Delete a Post
   */
  public function delete() {
    if (!user() || !user()->isAdmin()) {
      error('Error!: Action is not authorized');
    }

    $id = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);

    if (empty($id)) {
      error('Error!: Post ID invalid or missing');
    }

    $result = Post::delete($id);

    if ($result) {
      return redirect('/', 'Success!: Post Deleted!');
    }
    return redirect('/', 'Error!: Failed to Delete Post!');
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