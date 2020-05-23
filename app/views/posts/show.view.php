<?php partial('header', ['msg' => isset($msg) ? $msg : null]);?>

<div class="card">
  <div class="card-body text-dark bg-light">
    <article>
      <h3 class="display-3"><?= $post->title; ?></h3>
      <span class="text-muted">Posted on: <?= $post->posted_on; ?> By: <?= $post->author()->display_name; ?></span>
      <hr>
      <p><?= $post->body; ?></p>
    </article>
  </div>
</div>

<div class="card my-4">
  <div class="card-body">
    <form action="/comments/create">
      <fieldset>
        <legend>Leave a Comment</legend>
        <div class="form-group">
          <textarea class="form-control" name="body" required></textarea>
        </div>
        <input type="hidden" name="user_id" value="<?= user()->id; ?>">
        <input type="hidden" name="post_id" value="<?= $post->id; ?>">
        <div class="d-flex">
          <input type="submit" class="btn btn-primary ml-auto">
        </div>
      </fieldset>
    </form>
  </div>
</div>

<dl class="row">
  <?php foreach($comments as $comment): ?>
  <dt class="col-sm-2">
    <span><?= $comment->author()->display_name; ?></span>
  </dt>
  <dd class="col-sm-10">
    <p><?= $comment->body; ?></p>
  </dd>
  <?php endforeach; ?>
</dl>

<?php partial('footer'); ?>