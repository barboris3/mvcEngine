<?php
namespace application\controllers;

use application\core\Controller;
use application\core\View;

use application\lib\Pagination;

class ControllerMain extends Controller
{
	public function action_index()
	{
		$this->view->render('main');
	}
	
	public function action_admin()
	{
		/*'Showing private page available only for admin';*/
	}
	
	public function action_about()
	{
		$this->view->render('about');
	}
}
