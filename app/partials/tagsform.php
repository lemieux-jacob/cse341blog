<div class="card">
  <div class="card-body">
    <div class="card">
      <div class="card-header d-flex align-items-center">
        <div>
          <h3>Tags (click to remove)</h3>
        </div>
        <div class="ml-auto">
          <form method="POST" class="form-inline" action="/tags/add">
            <div class="form-group">
              <label for="newTagName mr-1">Add Tag: </label>
              <input id="newTagName" name="name" type="text" class="form-control">
              <input type="hidden" name="post_id" value="<?= $post->id; ?>">
              <input type="submit" value="Submit" class="btn btn-primary">
            </div>
          </form>
        </div>
      </div>
      <div class="card-body d-flex">
        <?php foreach($tags as $tag): ?>
        <div class="tag">
          <form class="form-inline" method="POST" action="/tags/remove">
            <input type="hidden" name="post_id" value="<?= $post->id; ?>">
            <input type="hidden" name="tag_id" value="<?= $tag->attr('id'); ?>">
            <input class="btn btn-secondary" type="submit" value="<?= $tag->attr('name'); ?> &cross;">
          </form>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>