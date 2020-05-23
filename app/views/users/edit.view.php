<?php partial('header', ['msg' => isset($msg) ? $msg : null]);?>

<?php partial('userform', array_merge($data, [
  'formTitle' => 'Edit User',
  'formAction' => '/users/update'
]));?>

<?php partial('footer');?>