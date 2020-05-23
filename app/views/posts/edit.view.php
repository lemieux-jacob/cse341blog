<?php partial('header', ['msg' => isset($msg) ? $msg : null]);?>

<?php partial('postform', [
  'formAction' => '/posts/update', 
  'formTitle' => 'Update Post', 
  'post' => $post,
  'form' => $form,
  'user' => $user
]); ?>

<?php partial('footer'); ?>