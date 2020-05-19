<?php
return [
  '/blog' => controller('Post', 'index'),
  '/post' => controller('Post', 'view'),
  '/post/create' => controller('Post', 'create'),
  '/post/edit' => controller('Post', 'edit'),
  '/post/update' => controller('Post', 'update'),
  '/post/delete' => controller('Post', 'delete'),
];