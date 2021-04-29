<?php
namespace application\controllers;

use application\core\Controller;
use application\core\View;

class ControllerMessages extends Controller
{
	public function action_index()
	{
		if(!empty($_POST['id'])) {
			$this->model->deleteMessages($_POST['id']);
			$this->view->methods(
				[
					'methods' => ['removeDialog'],
					'data' => ['removeDialog' => $_POST['id']]
				]);
		}
		$this->view->render('messages', $this->model->_default());
	}
	
	public function action_dialog()
	{
		if(!empty($_POST))
		{
			if($this->model->checkIsMessageIsset($_POST['message'])) {
				$this->view->message('enter your message');
			}
			$result = $this->model->getInterlocutor();
			$this->model->sendMessage(
				[
				'reciever' => $_SESSION['user']['login'] == $result['sender'] ? $result['reciever'] : $result['sender'], 
				'message' => $_POST['message'], 
				'id' => explode('/', $_SERVER['REQUEST_URI'])[2]
				]
			);
			$this->view->methods(
				[
					'methods' => ['resetForm']
				]);
		}
		
		$this->view->render('messages_dialog', $this->model->getDialog(), ['scripts' => ['sseChat']]);
	}
	
	public function action_new()
	{
		if(!empty($_POST))
		{
			if(!$this->model->checkUser($_POST['reciever'])) {
				$this->view->message('no such user');
			}
			if($this->model->checkIsMessageIsset($_POST['message'])) {
				$this->view->message('enter your message');
			}
			if(!$id = $this->model->checkDialog($_POST['reciever'])) {
				$this->view->location($_SERVER['HTTP_HOST'].'/messages/'.$this->model->newDialog($_POST));
			}
			$this->model->sendMessage(['reciever' => $_POST['reciever'], 'message' => $_POST['message'], 'id' => $id['id']]);
			$this->view->location($_SERVER['HTTP_HOST'].'/messages/'.$id['id']);
		}
		$this->view->render('messages_new', null);
	}
	
	public function action_sse()
	{
		session_write_close();
		$this->view->sseHeaders();
		while(true) {
			if($result = $this->model->getNewMessage()) {
				$this->view->sse(json_encode($result));
				flush();
				ob_end_flush();
			}
			sleep(0.5);
		}
	}
}
