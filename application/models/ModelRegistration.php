<?php
namespace application\models;
use application\core\model;

class ModelRegistration extends Model
{
	public function _default($params = null)
	{
		$_SESSION['user']['login'] = $params['login'];
		$_SESSION['user']['token'] = bin2hex(random_bytes(30));
		$this->db->execute_query(
			"INSERT INTO `users` (`id`, `login`, `email`, `password`, `image`, `name`, `about`, `registration`, `visit`, `token`, `block`)
						VALUES (NULL, :login, :email, :password, '', '', '', NOW(), NOW(), :token, 0)",
			[
				'login' => $params['login'],
				'email' => $params['email'],
				'password' => password_hash($params['password'], PASSWORD_DEFAULT),	
				'token' => $_SESSION['user']['token']
			]
		);
		
		$_SESSION['user']['id'] = $this->db->fetch(
			"SELECT `users`.`id` FROM `users` WHERE `login`= :login",
			['login' => $params['login'],]
			)['id'];
	}
	
	public function checkLogin($login)
	{
		if(!preg_match("/^[a-zA-Z0-9-_]{3,20}$/", $login)) {
			return false;
		}
		return true;
	}
	
	public function checkEmail($email)
	{
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return false;
		}
		return true;
	}
	
	public function checkRpassword($password, $rpassword)
	{
		if($password != $rpassword) {
			return false;
		}
		return true;
	}
	
	public function checkPassword($password)
	{
		if(!preg_match("/^[a-zA-Z0-9-_!@#$%^&*\:\/.,\'\"\`<>{}\[\]()]{5,25}$/", $password)) {
			return false;
		}
		return true;
	}
	
	public function checkUser($login, $email)
	{
		if($this->db->get_rowCount("SELECT * FROM `users` WHERE `login`= :login OR `email`= :email", ['login' => $login, 'email' => $email])) {
			return false;
		}
		return true;
	}
}
