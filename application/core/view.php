<?php
namespace application\core;

class View
{
	public function render($view, $data = null, $files = null)
	{
		include 'application/views/template.php';
	}
	
	public function location($url)
	{
		exit(json_encode(['url' => $url]));
	}
	
	public function message($message)
	{
		exit(json_encode(['message' => $message]));
	}
	
	public function console($message)
	{
		exit(json_encode(['console' => $message]));
	}
	
	public function methods($methods)
	{
		exit(json_encode($methods));
	}
	
	
	public function redirect($destination)
	{
		switch ($destination)
		{
			case 'home':
				header("Location: http://{$_SERVER['HTTP_HOST']}");
				exit();
				break;
			case 'self':
				header("Location: http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}");
				exit();
				break;
			case 404:
				header('HTTP/1.1 404 Not Found');
				header("Status: 404 Not Found");
				exit;
				break;				
			case 403:
				header('HTTP/1.1 403 Forbidden');
				header("Status: 403 Forbidden");
				exit;
				break;
			/*
			case 'smth else':
				header();
				exit();
				break;
			*/
		}
	}
}
