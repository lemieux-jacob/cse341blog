<?php partial('header'); ?>

<div class="d-flex">
  <div class="px-4">
    <h2>Posts</h2>
  </div>
</div>

<?php foreach($posts as $post): ?>
<div class="card rounded-0 my-3">
  <div class="card-body bg-gradient-dark">
    <a href=/posts/view?id=<?= $post->id ?>">
      <h3 class="display-4"><?= $post->title; ?></h3>
    </a>
    <div class="d-flex">
      <div class="small text-muted p-3">
        Posted on: <?= $post->posted_on; ?> By: <?= $post->author()->display_name; ?>
      </div>
      <a class="btn btn-primary ml-auto" href="/posts/view?id=<?= $post->id ?>">View Post</a>
      <?php if (access($post)): ?>
      <a class="btn btn-primary mx-1" href="/posts/edit?id=<?= $post->id ?>">Edit Post</a>
      <form method="POST" class="form-inline m-0" action="/posts/delete">
        <input type="hidden" name="post_id" value="<?= $post->id; ?>">
        <input class="btn btn-danger" type="submit" value="Delete">
      </form>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php endforeach; ?>

<?php partial('footer'); ?>