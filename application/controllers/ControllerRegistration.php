<?php
namespace application\controllers;

use application\core\Controller;
use application\core\View;

class ControllerRegistration extends Controller
{
	public function action_index()
	{
		if(!empty($_SESSION['user']))
		{
			$this->view->redirect('home');
		}
		
		if(!empty($_POST['login']))
		{
			$errors = '';
			if(!$this->model->checkLogin($_POST['login'])) {
				$errors .= "Login length must be from 3 to 15 sybmols, contains only eng letters, numbers, - or _.\n";
			}
			if(!$this->model->checkUser($_POST['login'] ,$_POST['email'])) {
				$errors .= "Such user already exsists or email already used.\n";
			}
			if(!$this->model->checkEmail($_POST['email'])) {
				$errors .= "Invalid email format.\n";
			}
			if(!$this->model->checkPassword($_POST['password'])) {
				$errors .= "Password can only contains eng letters, numbers or spec symbols, length from 5 to 25.\n";		
			}
			if(!$this->model->checkRpassword($_POST['password'], $_POST['rpassword'])) {
				$errors .= "Passwords do not match.\n";
			}
			if(!empty($errors)) {
				$this->view->message($errors);
			}
			else {
				$this->model->_default($_POST);
				$this->view->location($_SERVER['HTTP_HOST']);
			}
		}
		
		$this->view->render('registration');
	}
}
