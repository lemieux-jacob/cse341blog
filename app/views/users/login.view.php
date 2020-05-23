<?php partial('header', ['msg' => isset($msg) ? $msg : null]);?>

<div class="card">
  <div class="card-header">
    Login
  </div>
  <div class="card-body">
    <form class="form" method="POST" action="/handle-login">
      <div class="form-group">
        <label for="email">Email: </label>
        <input class="form-control" type="text" for="email" name="email" id="email">
      </div>
      <div class="form-group">
        <label for="password">Password: </label>
        <input class="form-control" type="password" for="password" name="password" id="password">
      </div>
      <input class="btn btn-primary btn-block" type="submit">
    </form>
  </div>
  <div class="card-footer d-flex">
    <a href="/" class="btn btn-primary ml-auto">Cancel</a>
  </div>
</div>

<?php partial('footer'); ?>