<?php
namespace application\controllers;

use application\core\Controller;
use application\core\View;

class ControllerLogout extends Controller
{	
	public function action_index()
	{
		session_destroy();
		$this->view->redirect('home');
	}
}
