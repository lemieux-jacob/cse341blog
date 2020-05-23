<?php partial('header', ['msg' => isset($msg) ? $msg : null]);?>

<div class="card">
  <div class="card-header">
    Dashboard
  </div>
  <div class="card-body">
    <dl class="row">
      <dt class="col-sm-2">Display Name: </dt>
      <dd class="col-sm-10"><?= $user->attr('display_name'); ?></dd>
      <dt class="col-sm-2">Email:</dt>
      <dd class="col-sm-10"><?= $user->attr('email');?></dd>
    </dl>
  </div>
  <div class="card-footer p-0 d-flex">
    <a class="btn btn-primary ml-auto" href="/users/edit">Change Account</a>
  </div>
</div>

<?php partial('footer');?>