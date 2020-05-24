<?php
namespace App\Controllers;

use App\Models\Tag;
use App\Models\Post;

class TagController {

  function __construct() {
    if (!user() && !user()->isAdmin()) {
      error('Error!: Action is not authorized');
    }
  }

  /**
   * Add Tag(s) to Post
   */
  public function add() {
    $post_id = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);

    if (empty($post_id)) {
      error('Error!: Post ID is missing');
    }

    if (empty($name)) {
      return redirect('/posts/edit?id=' . $post_id, "Notice!: Please enter a valid tagname");
    }

    if (!$post = Post::fetch($post_id)) {
      error('Error!: Could not find Post with ID# ' . $post_id);
    }

    $existing_tags = $post->tags();

    // Convert Tag to Lowercase
    $name = strtolower($name);

    foreach ($existing_tags as $t) {
      if ($t->attr('name') === $name) {
        return redirect('/posts/edit?id=' . $post_id, 'Notice!: Tag already applied to Post');
      }
    }

    $tag = Tag::fetch($name);

    if (!$tag) {
      $tag = Tag::create([
        'name' => $name
      ]);
    }

    $result = $post->addTag($tag->attr('id'));

    if ($result) {
      return redirect('/posts/edit?id=' . $post_id, 'Success!: Added Tag!');
    }
    return redirect('/posts/edit?id=' . $post_id, 'Error!: Failed to Add Tag');
  }

  /**
   * Remove Tag(s) from Post
   */
  public function remove() {
    $post_id = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);
    $tag_id = filter_input(INPUT_POST, 'tag_id', FILTER_SANITIZE_STRING);

    if (empty($post_id) || empty($tag_id)) {
      return redirect('/', 'Error!: Missing Post or Tag ID');
    }

    $result = Tag::delete($tag_id, $post_id);

    if ($result) {
      return redirect('/posts/edit?id=' . $post_id, 'Success!: Tag Removed!');
    }
    return redirect('/posts/edit?id=' . $post_id, 'Error!: Failed to Remove Tag');
  }
}