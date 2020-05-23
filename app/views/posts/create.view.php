<?php partial('header', ['msg' => isset($msg) ? $msg : null]);?>

<?php partial('postform', [
  'formAction' => '/posts/store', 
  'formTitle' => 'Create Post',
  'form' => $form,
  'user' => $user
]);?>

<?php partial('footer'); ?>