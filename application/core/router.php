<?php
namespace application\core;

class Router
{
	private $routes = [];
	private $params = [];
	
	public function __construct()
	{
		$routes = require 'application/config/routes.php';
		foreach ($routes as $key => $value) {
			$this->add($key, $value);
		}
	}
	
	public function add($route, $params) 
	{
		$route = '#^'.$route.'$#';
		$this->routes[$route] = $params;
	}
	
	public function match() 
	{
		$url = trim($_SERVER['REQUEST_URI'], '/');
		//echo 'url is '.$url.'<br>';
		foreach ($this->routes as $route => $params) {
			// echo $route.'<br>';
			if (preg_match($route, $url)) {
				$this->params = $params;
				return true;
			}
		}
		return false;
	}
	
	public function route()
	{
		if($this->match()) {
			$path = 'application\controllers\Controller'.ucfirst($this->params['controller']);
			if (class_exists($path)) {
				$action = 'action_'.$this->params['action'];
				
				// echo $action;
				// echo '<br>';
				// echo $path;
				// echo '<br>';
				
				if (method_exists($path, $action)) {
					$controller = new $path($this->params);
					$controller->$action();
				} else {
					$this::ErrorPage404();
					// echo '404 - no such method';
					//View::errorCode(404);
				}
			} else {
				$this::ErrorPage404();
				// echo '404 - no such class';
				//View::errorCode(404);
			}
		} else {
			$this::ErrorPage404();
				// echo '404 - no such url in routes';
				// View::errorCode(404);
		}
	}
	
	private function ErrorPage404()
	{
		header('HTTP/1.1 404 Not Found');
		header("Status: 404 Not Found");
	}
}
