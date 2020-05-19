<?php
function render($name, $data = []) {
  extract($data);

  return require "app/{$name}.php";
}

// Plural: Posts, Comments, Tags
function crud($controller) {
  return [
    strPlural($controller) => controller($controller, 'index'),
    "{$controller}" => controller($controller, 'show'),
    "{$controller}/create" => controller($controller, 'create'),
    "{$controller}/edit" => controller($controller, 'edit'),
    "{$controller}/update" => controller($controller, 'update'),
    "{$controller}/delete" => controller($controller, 'delete')
  ];
}

/**
 * Pluralize Word
 * A stub for a more robust solution or dependancy.
 */
function strPlural($str) {
  return $str . "s";
}