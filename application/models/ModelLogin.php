<?php
namespace application\models;
use application\core\model;

class ModelLogin extends Model
{
	public function _default($params = null)
	{
		$result = $this->db->fetch(
			"SELECT * FROM `users` WHERE `login` = :login",
			['login' => $params['login']]
		);
		
		if(!$result['password'])
			return 'No such user';
		if(!password_verify($params['password'], $result['password']))
			return 'Wrong password';
		if($this->isUserBlocked($params['login']))
			return 'This user is blocked';
		
		$_SESSION['user']['id'] = $result['id'];
		$_SESSION['user']['login'] = $result['login'];
		$_SESSION['user']['token'] = bin2hex(random_bytes(30));
		
		$this->db->execute_query(
			"UPDATE `users` SET `token` = :token, `visit` = NOW() WHERE `users`.`id` = :id",
			['token' => $_SESSION['user']['token'], 'id' => $_SESSION['user']['id']]
		);
	}
	
	public function isUserBlocked($login) {
		if($this->db->get_rowCount("SELECT * FROM `users` WHERE `login` = :login AND `block` = 1", ['login' => $login])) {
			return true;
		}
		return false;
	}
}
