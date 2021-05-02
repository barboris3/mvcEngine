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
		foreach ($this->routes as $route => $params) {
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
				
				if (method_exists($path, $action)) {
					$controller = new $path($this->params);
					$controller->$action();
				} else {
					$this::ErrorPage404();
					// '404 - no such method';
				}
			} else {
				$this::ErrorPage404();
				// '404 - no such class';
			}
		} else {
			$this::ErrorPage404();
			// '404 - no such url in routes';
		}
	}
	
	private function ErrorPage404()
	{
		header('HTTP/1.1 404 Not Found');
		header("Status: 404 Not Found");
	}
}
