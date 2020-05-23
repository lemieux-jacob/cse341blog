<div class="card">
  <div class="card-header">
    <?= $formTitle; ?>
  </div>
  <div class="card-body">
    <form class="form" action="<?= $formAction ;?>" method="POST">
      <div class="form-group">
        <label for="posttitle">Title: </label>
        <input class="form-control" name="title" id="posttitle" type="text" value="<?php if (isset($form['title'])) echo $form['title']; 
        else if (isset($post->title)) echo $post->title; ?>" autofocus>
      </div>
      <label for="postbody">Body: (Uses Markdown for Formating)</label>
      <textarea class="form-control" name="body" id="postbody" rows="10" type="text"><?php if (isset($form['body'])) echo $form['body']; else if (isset($post->body)) echo $post->body; ?></textarea>
      <?php if (isset($post)): ?>
        <input name="post_id" type="hidden" value="<?php echo $post->id; ?>">
      <?php endif;?>
      <input name="user_id" type="hidden" value="<?php echo $post->user_id ?? $user->id;?>">
      <input class="btn btn-primary btn-block" type="submit" value="Submit">
    </form>
  </div>
  <div class="card-footer text-right p-1">
    <a class="btn btn-primary" href="/posts">Back to Posts</a>
  </div>
</div>