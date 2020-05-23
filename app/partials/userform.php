<div class="card">
  <div class="card-header">
    <?= $formTitle; ?>
  </div>
  <div class="card-body">
    <form method="POST" action="<?= $formAction; ?>" class="form">
      <div class="form-group">
        <label for="display_name">Display Name: </label>
        <input id="display_name" class="form-control" type="text" name="display_name" value="<?php if (isset($form['display_name'])) echo $form['display_name']; 
        else if (isset($user->display_name)) echo $user->display_name; ?>" required autofocus>
      </div>
      <?php if ($formTitle === "Edit User"): ?>
      <div class="form-group">
        <label for="old-password">Current Password: </label>
        <input id="old-password" class="form-control" type="password" name="old_password" placeholder="password" required>
      </div>
      <?php else: ?>
      <div class="form-group">
        <label for="email">Email: </label>
        <input id="email" class="form-control" type="email" name="email" placeholder="example@domain.com" value="<?php if (isset($form['email'])) echo $form['email']; 
        else if (isset($user->email)) echo $user->email; ?>" required>
      </div>
      <?php endif; ?>
      <div class="form-group">
        <label for="password">Password: </label>
        <p class="small">Minimum eight characters, at least one letter, one number and one special character.</p>
        <input id="password" class="form-control" type="password" name="password" placeholder="password" required>
      </div>
      <div class="form-group">
        <label for="confirm_password">Confirm Password: </label>
        <input id="confirm_password" class="form-control" type="password" name="confirm_password" placeholder="confirm password" required>
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