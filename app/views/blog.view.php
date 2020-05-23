<?php partial('header'); ?>

<?php foreach($posts as $post): ?>
<div class="card rounded-0">
  <div class="card-body bg-light">
    <h3 class="display-4"><?= $post->title; ?></h3>
  </div>
  <div class="card-foot d-flex">
    <div class="small text-muted p-3">
      Posted on: <?= $post->posted_on; ?> By: <?= $post->author()->display_name; ?>
    </div>
    <a class="btn btn-primary ml-auto" href="/posts/view?id=<?= $post->id ?>">View Post</a>
    <?php if (user() && user()->isAdmin()): ?>
    <a class="btn btn-primary" href="/posts/edit?id=<?= $post->id ?>">Edit Post</a>
    <?php endif; ?>
  </div>
</div>
<?php endforeach; ?>

<?php partial('footer'); ?>