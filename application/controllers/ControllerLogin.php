<?php
namespace application\controllers;

use application\core\Controller;
use application\core\View;

class ControllerLogin extends Controller
{	
	public function action_index()
	{		
		if(!empty($_SESSION['user']))
		{
			$this->view->redirect('home');
		}
		
		if(!empty($_POST['login']))
		{
			
			$result = $this->model->_default($_POST);
			if($result){
				$this->view->message($result);
			}
			$this->view->location($_SERVER['HTTP_HOST']);
		}
		
		$this->view->render('login');
	}
}