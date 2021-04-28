<?php
namespace application\controllers;

use application\core\Controller;
use application\core\View;

class ControllerProfile extends Controller
{
	public function action_index()
	{
		$this->view->render('profile', $this->model->_default());
	}
}
