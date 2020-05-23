<?php partial('header'); ?>

<div class="d-flex">
  <div class="px-4">
    <h2>Posts</h2>
  </div>
</div>

<?php foreach($posts as $post): ?>
<div class="card rounded-0 my-3">
  <a href=/posts/view?id=<?= $post->id ?>">
    <div class="card-body bg-gradient-dark">
      <h3 class="display-4"><?= $post->title; ?></h3>
    </div>
  </a>
  <div class="d-flex">
    <div class="small text-muted p-3">
      Posted on: <?= $post->posted_on; ?> By: <?= $post->author()->display_name; ?>
    </div>
    <a class="btn btn-primary ml-auto" href="/posts/view?id=<?= $post->id ?>">View Post</a>
    <?php if (user() && user()->isAdmin()): ?>
    <a class="btn btn-primary mx-1" href="/posts/edit?id=<?= $post->id ?>">Edit Post</a>
    <?php endif; ?>
  </div>
</div>
<?php endforeach; ?>

<?php partial('footer'); ?>