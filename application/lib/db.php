<?php
namespace application\lib;
use PDO;

class Db
{
	protected $db;
	
	public function __construct() {
		$config = require 'application/config/db.php';
		$this->db = new PDO("mysql:host={$config['host']};dbname={$config['dbname']}", $config['user'], $config['password']);
	}
	
	private function bind_params($stmt, $params) {
		if($params) {
			foreach($params as $key => &$value) {
				if(is_int($value)) {
					$type = PDO::PARAM_INT;
				} else {
					$type = PDO::PARAM_STR;
				}
				$stmt->bindParam($key, $value, $type);
			}
		}
		return $stmt;
	}
	
	public function get_rowCount($query, $params = null) {
		$stmt = $this->db->prepare($query);
		$stmt->execute($params);
		return $stmt->rowCount();
	}
	
	public function execute_query($query, $params = null) {
		$stmt = $this->db->prepare($query);
		$stmt = $this->bind_params($stmt, $params);
		$stmt->execute();
	}
	
	public function fetch($query, $params = null) {
		$stmt = $this->db->prepare($query);
		$stmt = $this->bind_params($stmt, $params);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
	
	public function fetch_all($query, $params = null) {
		$stmt = $this->db->prepare($query);
		$stmt = $this->bind_params($stmt, $params);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function get_lastInsertId() {
		return $this->db->lastInsertId();
	}
}
