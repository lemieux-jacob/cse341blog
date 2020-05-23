<?php partial('header', ['msg' => isset($msg) ? $msg : null]);?>

<div class="card">
  <div class="card-body text-dark bg-light">
    <article>
      <h3 class="display-3"><?= $post->title; ?></h3>
      <hr>
      <span class="text-muted">Posted on: <?= $post->posted_on; ?> By: <?= $post->author()->display_name; ?></span>
      <hr>
      <hr>
      <?= $post->body; ?>
    </article>
  </div>
</div>

<?php if (user()): ?>
<div class="card my-4">
  <div class="card-body pb-1">
    <form class="form" method="POST" action="/comments/store">
      <fieldset>
        <legend>Leave a Comment</legend>
        <div class="form-group mb-1">
          <textarea class="form-control mb" name="body" required></textarea>
        </div>
        <input type="hidden" name="user_id" value="<?= user()->id; ?>">
        <input type="hidden" name="post_id" value="<?= $post->id; ?>">
        <div class="d-flex">
          <input type="submit" value="Submit" class="btn btn-primary ml-auto">
        </div>
      </fieldset>
    </form>
  </div>
</div>
<?php endif; ?>

<div class="m-3">
  <h3>Comments</h3>
</div>

<?php foreach($comments as $comment): ?>
<div class="card">
  <div class="card-header">
    <?php if (access($comment) === 'owner'): ?>
    <span><?= $comment->author()->display_name; ?><b>(You)</b> Replied on: <?= $comment->posted_on; ?>:</span>
    <?php else: ?>
    <span><?= $comment->author()->display_name; ?>Replied on: <?= $comment->posted_on; ?>: </span>
    <?php endif; ?>
  </div>
  <div class="card-body bg-light text-dark">
    <p><?= $comment->body; ?></p>
  </div>
  <div class="card-footer">
    <?php if (auth($comment)): ;?>
    <div class="d-flex">
      <div class="d-flex ml-auto">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#CommentModal"
          data-post="<?= $comment->post_id; ?>" data-user="<?= $comment->user_id; ?>"
          data-comment="<?= $comment->id; ?>" data-body="<?= $comment->body;?>">Edit</button>
      </div>
      <div>
        <form method="POST" class="form-inline m-0" action="/comments/delete">
          <input type="hidden" name="post_id" value="<?= $post->id; ?>">
          <input type="hidden" name="comment_id" value="<?= $comment->id; ?>">
          <input class="btn btn-danger ml-1" type="submit" value="Delete">
        </form>
      </div>
    </div>
    <?php endif; ?>
    <?php endforeach; ?>
  </div>
</div>

<?php partial('footer'); ?>

<div class="modal fade" id="CommentModal" tabindex="-1" role="dialog" aria-labelledby="CommentModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="CommentModalLabel">Edit Comment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span class="text-light" aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="editCommentForm" method="POST" action="/comments/update">
          <div class="form-group">
            <label for="bodyText">Comment: </label>
            <textarea id="bodyText" name="body" class="form-control"></textarea>
          </div>
          <input type="hidden" name="comment_id" id="commentID">
          <input type="hidden" id="postID" name="post_id" value="<?= $post->id; ?>">
          <input type="hidden" id="userID" name="user_id">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" form="editCommentForm">Update Comment</button>
      </div>
    </div>
  </div>
</div>

<script src="/scripts/commentForm.js"></script>