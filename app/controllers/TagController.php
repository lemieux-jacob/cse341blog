<?php
namespace App\Controllers;

use App\Models\Tag;

class TagController {

  function __construct() {
    if (!user()) {
      dd('Error!: Action is not authorized');
    }
  }

  public function add() {
    $form = [
      'post_id' => filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT),
      'name' => filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING)
    ];

    if (empty($data['name'])) {
      return redirect('/post');
    }

    $tag = Tag::fetch($form['name']);

    if (!$tag) {
      $tag = Tag::create($form['post_id']);
    }

    $result = Tag::addPostTag($tag->id, $form['post_id']);

    if ($result) {
      return redirect();
    }
    return redirect();
  }

  public function remove() {
    //
  }

}