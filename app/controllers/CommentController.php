<?php
namespace App\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

class CommentController {

  /**
   * Create a new Comment :: Action
   * Form is in views/posts/show.view.php
  */
  public function store() {
    if (!user()) {
      return redirect('/login', 'Please login before commenting');
    }

    $form = $this->validateComment([
      'user_id' => filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT),
      'post_id' => filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT),
      'body' => filter_input(INPUT_POST, 'body', FILTER_SANITIZE_STRING)
    ]);

    // dd(Post::fetch($form['post_id']));

    if (!$form['is_valid']) {
      if (!empty($form['post_id']) || !Post::fetch($form['post_id'])) {
        return redirect('/posts/view?id=' . $form['post_id'], 'Notice!: Invalid or empty comment');
      }
      dd('Error!: Unable to fetch Post with ID# ' . $form['post_id']);
    }

    if (!User::fetch($form['user_id'])) {
      dd('Error!: Unable to fetch User with ID# ' . $form['user_id']);
    }

    $result = Comment::create($form);

    if ($result) {
      return redirect('/posts/view?id=' . $form['post_id'], 'Success!: Comment Created!');
    }
    return redirect('/posts/view?id=' . $form['post_id'], 'Error!: Comment creation failed');
  }

  /**
   * Edit a Comment :: Action
   * Form is in views/posts/show.view.php
  */
  public function update() {

    $form = $this->validateComment([
      'comment_id' => filter_input(INPUT_POST, 'comment_id', FILTER_SANITIZE_NUMBER_INT),
      'user_id' => filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT),
      'post_id' => filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT),
      'body' => filter_input(INPUT_POST, 'body', FILTER_SANITIZE_STRING)
    ]);

    if (!$form['is_valid']) {
      if (!empty($form['post_id'])) {
        return redirect('/posts/view?id=' . $form['post_id'], 'Notice!: Invalid or empty comment');
      }
      return redirect('/', 'Error!: Missing Post ID');
    }

    $comment = Comment::fetch($form['comment_id']);

    if (!$comment || !auth($comment)) {
      dd('Error!: This action is not authorized');
    }

    $result = $comment->update($form);

    if ($result) {
      return redirect('/posts/view?id=' . $form['post_id'], 'Success!: Comment Updated!');
    }
    return redirect('/posts/view?id=' . $form['post_id'], 'Error!: Failed to update Comment');
  }

  public function delete() {
    $comment_id = filter_input(INPUT_POST, 'comment_id', FILTER_SANITIZE_NUMBER_INT);

    if (empty($comment_id)) {
      dd('Error!: Comment does not exist');
    }

    if (!$comment && !auth($comment)) {
      dd('Error!: This action is not authorized');
    }

    Comment::delete($comment_id);

    if ($result) {
      return redirect('/posts/view?id=' . $form['post_id'], 'Success!: Comment Deleted!');
    }
    return redirect('/posts/view?id=' . $form['post_id'], 'Error!: Failed to delete Comment');
  }

  protected function validateComment($form) {
    foreach($form as $field) {
      if (empty($field)) {
        $form['is_valid'] = false;
      } else if (!strlen(trim($form['body']))) {
        $form['is_valid'] = false;
      } else {
        $form['is_valid'] = true;
      }
      return $form;
    }
  }
  
}