<?php

class App
{

  protected $actualPath;
  protected $actualMethod;
  protected $routes = [];
  protected $notFound;


  public function __construct($currentPath, $currentMethod)
  {
    if ($currentPath == '') {
      $currentPath = '/';
    }
    $this->actualPath = $currentPath;
    $this->actualMethod = $currentMethod;

    // 404
    $this->notFound = function () {
      require_once StaticFunctions::View('V' . '/page.404.php');
    };
  }


  public function get($path, $callback)
  {
    $this->routes[] = ['GET', $path, $callback];
  }


  public function post($path, $callback)
  {
    $this->routes[] = ['POST', $path, $callback];
  }

  public function run()
  {
    foreach ($this->routes as $route) {
      list($method, $path, $callback) = $route;

      $checkMethod = $this->actualMethod == $method;
      $checkPath = preg_match("~^{$path}$~ixs", $this->actualPath, $params);

      if ($checkMethod && $checkPath) {
        array_shift($params);
        return call_user_func_array($callback, $params);
      }
    }

    return call_user_func($this->notFound);
  }
}

$route_path = rtrim(urldecode(strtok($_SERVER["REQUEST_URI"], '?')), '/');
$route_method = $_SERVER['REQUEST_METHOD'];
$route_path = (str_replace(PATH, '/', $route_path) == '') ? '/' : str_replace(PATH, '/', $route_path);
$route_path = AppLanguage::UrlMaker($route_path);
$App = new App($route_path, $route_method);
