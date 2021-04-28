<?php
namespace application\models;
use application\core\model;

class ModelProfile extends Model
{
	public function _default($params = null)
	{
		return $this->db->fetch("SELECT
				`users`.`id`, 
				`users`.`login`, 
				`users`.`email`, 
				`users`.`registration`, 
				`users`.`visit`, 
				`users`.`block`
				FROM `users` WHERE `login` = :login", 
				[
				'login' => explode('/', $_SERVER['REQUEST_URI'])[2]
				]
				);
	}
}
