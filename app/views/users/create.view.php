<?php partial('header', ['msg' => isset($msg) ? $msg : null]);?>

<div class="card">
  <div class="card-header">
    Register
  </div>
  <div class="card-body">
    <form method="POST" action="/users/store" class="form">
      <div class="form-group">
        <label for="display_name">Display Name: </label>
        <input class="form-control" type="text" name="display_name" value="<?php if (isset($form['display_name'])) echo $form['display_name']; 
        else if (isset($user->display_name)) echo $user->display_name; ?>" required autofocus>
      </div>
      <div class="form-group">
        <label for="email">Email: </label>
        <input class="form-control" type="email" name="email" placeholder="example@domain.com" value="<?php if (isset($form['email'])) echo $form['email']; 
        else if (isset($user->email)) echo $user->email; ?>" required>
      </div>
      <div class="form-group">
        <label for="password">Password: </label>
        <p class="small">Minimum eight characters, at least one letter, one number and one special character.</p>
        <input class="form-control" type="password" name="password" placeholder="password" required>
      </div>
      <div class="form-group">
        <label for="confirm_password">Confirm Password: </label>
        <input class="form-control" type="password" name="confirm_password" placeholder="confirm password" required>
      </div>
      <input type="hidden" name="user_id" value="<?= $user->id; ?>">
      <input class="btn btn-primary btn-block" type="submit" value="Submit">
    </form>
  </div>
  <div class="card-footer text-right p-1 d-flex">
    <a class="btn btn-primary ml-auto mr-1" href="users/login">Login</a>
    <a class="btn btn-primary" href="/">Cancel</a>
  </div>
</div>

<?php partial('footer'); ?>