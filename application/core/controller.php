<?php
namespace application\core;
use application\core\view;

abstract class Controller
{
	private $route;
	private $acl;
	
	public $model;
	public $view;
	
	public function __construct($route)
	{
		$this->route = $route;
		
		$this->view = new View($route);
		if(!$this->checkAcl()) {
			$this->view->redirect(403);
		}
		$this->model = $this->createModel($route['controller']);		
	}
	
	public function createModel($name)
	{
		$path = 'application\models\Model'.($name);
		if (class_exists($path)) {
			return new $path;
		}
	}
	
	public function checkAcl() {
		$this->acl = require 'application/acl/'.$this->route['controller'].'.php';
		if($this->isAcl('all')) {
			// allow for all users;
			return true;
		}
		elseif(!isset($_SESSION['user']) && $this->isAcl('guest')) {
			//allow for guests;
			return true;
		}
		elseif(isset($_SESSION['user']) && $this->isAcl('authorize')) {
			//allow for auth users;
			return true;
		}
		elseif(isset($_SESSION['user']) && $_SESSION['user']['login'] == 'admin' && $this->isAcl('admin')) {
			//allow for admin;
			return true;
		}
		return false;
	}

	public function isAcl($key)
	{		
		return in_array($this->route['action'], $this->acl[$key]);
	}
	
	abstract protected function action_index();
}
