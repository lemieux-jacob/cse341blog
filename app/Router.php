<?php
namespace App;

// A Router Class

class Router {

  private $routes = [];

  function __construct($routes) {
    $this->routes = $routes;
  }

  public function direct($uri, $method = 'GET') {
    if (array_key_exists($uri, $this->routes)) {
      $action = $this->routes[$uri];

      // Handle Clossure
      if (is_callable($this->routes[$uri])) {
        return $this->route[$uri]();
      }

      // Handle Controller / Method
      $arr = explode('@', $this->routes[$uri]);

      $controller = '\\App\\Controllers\\' . $arr[0];

      if (!class_exists($controller)) {
        dd('Error!: Controller does not exist.');
      }

      if (!isset($arr[1])) {
        $method = 'index';
      } else {
        $method = $arr[1];
      }

      if (!method_exists($controller, $method)) {
        dd('Error!: Action does not exist on Controller.');
      }

      $controller = new $controller;
      
      return $controller->$method();
    }

    dd('Error!: Route does not exist.');
  }

}