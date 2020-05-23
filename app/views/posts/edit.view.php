<?php partial('header', ['msg' => isset($msg) ? $msg : null]);?>

<?php partial('postform', array_merge($data, ['formTitle' => 'Edit Post', 'formAction' => '/posts/update']));?>

<?php partial('tagsform', ['tags' => $tags, 'post' => $post]); ?>

<?php partial('footer'); ?>