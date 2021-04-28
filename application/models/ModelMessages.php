<?php
namespace application\models;
use application\core\model;

class ModelMessages extends Model
{
	protected $qtyOfMsg;
	
	public function _default($params = null)
	{
		$result = $this->db->fetch_All(
			"SELECT `id`,`sender`,`reciever`, MAX(`date`) as `date`, `dialog` FROM `messages` 
			WHERE (`sender` = :login AND `del_sender` = 0) OR (`reciever` = :login AND `del_reciever` = 0) 
			GROUP BY `dialog` ORDER BY `date` ASC",
			['login' => $_SESSION['user']['login']]
		);
		if($result) {
			foreach($result as $key => $value)
			{
				$interlocutor = $_SESSION['user']['login'] == $value['reciever'] ? $value['sender'] : $value['reciever'];
				
				if(!$this->checkIsInArray($interlocutor, $result, $key))
					$list[] = [
								'id' => $value['id'],
								'user' => $interlocutor,
								'unreadCount' => $this->db->get_rowCount(
									"SELECT * FROM `messages` WHERE `reciever` = :reciever AND `sender` = :sender AND `read_reciever` = 0 AND `del_reciever` = 0",
										[
											'reciever' => $_SESSION['user']['login'],
											'sender' => $interlocutor
										]
									),
								'date' => $value['date'],
								'dialog' => $value['dialog'],
							];
			}
			return array_reverse($list);
		}
	}
	
	private function checkIsInArray($interlocutor, $array, $key)
	{
		for ($i = $key + 1; $i < count($array); $i++) {
			if(in_array($interlocutor, $array[$i])) {
				return true;
			}
		}
		return false;
	}
	
	public function getDialog()
	{
		$dialog = explode('/', $_SERVER['REQUEST_URI'])[2];
		$this->db->execute_query(
			"UPDATE `messages` SET `read_reciever` = '1' WHERE `reciever` = :reciever AND `dialog` = :dialog",
			[
				'reciever' => $_SESSION['user']['login'],
				'dialog' => $dialog
			]
		);
		
		return $this->db->fetch_All(
			"SELECT `sender`,`reciever`,`message`,`date` FROM `messages` 
			WHERE (`sender` = :login AND `del_sender` = 0 OR `reciever` = :login AND `del_reciever` = 0) AND `dialog` = :dialog",
			[
				'login' => $_SESSION['user']['login'],
				'dialog' => $dialog
			]
		);
	}
	
	public function checkUser($user)
	{
		return $this->db->get_rowCount("SELECT * FROM `users` WHERE `login` = :login",
					['login' => $user]);
	}
	
	public function checkDialog($reciever)
	{
		return $this->db->fetch("SELECT `id` FROM `dialogs` 
		WHERE `reciever` = :reciever AND `sender` = :sender
		OR `reciever` = :sender AND `sender` = :reciever",
					[
					'reciever' => $reciever,
					'sender' => $_SESSION['user']['login'],
					]);
	}
	
	public function newDialog($params)
	{
		$this->db->execute_query(
			"INSERT INTO `dialogs` (`id`, `sender`, `reciever`) 
			VALUES (NULL, :sender, :reciever)",
			[
				'sender' => $_SESSION['user']['login'],
				'reciever' => $params['reciever']
			]
		);
		$this->db->execute_query(
			"INSERT INTO `messages` (`id`, `sender`, `reciever`, `message`, `date`, `read_reciever`, `del_sender`, `del_reciever`, `dialog`) 
			VALUES (NULL, :sender, :reciever, :message, NOW(), 0, 0, 0, :dialog)",
			[
				'sender' => $_SESSION['user']['login'],
				'reciever' => $params['reciever'],
				'message' => $params['message'],
				'dialog' => $id = $this->db->get_lastInsertId()
			]
		);
		return $id;
	}
	
	public function sendMessage($params)
	{
		$this->db->execute_query(
			"INSERT INTO `messages` (`id`, `sender`, `reciever`, `message`, `date`, `read_reciever`, `del_sender`, `del_reciever`, `dialog`) 
			VALUES (NULL, :sender, :reciever, :message, NOW(), 0, 0, 0, :dialog)",
			[
				'sender' => $_SESSION['user']['login'],
				'reciever' => $params['reciever'],
				'message' => $params['message'],
				'dialog' => $params['id']
			]
		);
	}
	
	public function getInterlocutor()
	{
		return $this->db->fetch("SELECT * FROM `dialogs` 
			WHERE `id` = :id AND `sender` = :login OR `reciever` = :login",
					[
					'id' => explode('/', $_SERVER['REQUEST_URI'])[2],
					'login' => $_SESSION['user']['login'],
					]);
	}
	
	public function deleteMessages($id)
	{
		$this->db->execute_query(
			"UPDATE `messages` SET `del_reciever` = 1 WHERE `reciever` = :login AND `dialog` = :id",
			[
				'login' => $_SESSION['user']['login'],
				'id' => $id
			]
		);
		$this->db->execute_query(
			"UPDATE `messages` SET `del_sender` = 1 WHERE `sender` = :login AND `dialog` = :id",
			[
				'login' => $_SESSION['user']['login'],
				'id' => $id
			]
		);
		$this->db->execute_query("DELETE FROM `messages` WHERE `del_sender` = 1 AND `del_reciever` = 1");
		if(!$this->db->get_rowCount("SELECT * FROM `messages` WHERE `dialog` = :id",['id' => $id]))
		{
			$this->db->execute_query("DELETE FROM `dialogs` WHERE `id` = :id", ['id' => $id]);
		}
	}
	
	public function checkIsMessageIsset($message) {
		return trim($message) === '';
	}
	
	public function getNewMessage()
	{
		$dialog = explode('/', $_SERVER['REQUEST_URI'])[3];
		$newquantity = $this->db->get_rowCount(
			"SELECT `sender`,`reciever`,`message`,`date` FROM `messages` 
			WHERE (`sender` = :login AND `del_sender` = 0 OR `reciever` = :login AND `del_reciever` = 0) AND `dialog` = :dialog",
			[
				'login' => $_SESSION['user']['login'],
				'dialog' => $dialog
			]
		);
		$this->qtyOfMsg = $this->qtyOfMsg ?? $newquantity;
		if($this->qtyOfMsg == $newquantity) {
			return;
		}
		
		$this->db->execute_query(
			"UPDATE `messages` SET `read_reciever` = '1' 
			WHERE `dialog` = :dialog AND `reciever` = :reciever",
			[
				'dialog' => $dialog,
				'reciever' => $_SESSION['user']['login']
			]
		);
		
		$diff = $newquantity - $this->qtyOfMsg;
		$oldquantity = $this->qtyOfMsg;
		$this->qtyOfMsg = $newquantity;
		return $this->db->fetch_All(
			"SELECT `sender`,`message`,`date` FROM `messages` 
			WHERE (`sender` = :login AND `del_sender` = 0 OR `reciever` = :login AND `del_reciever` = 0) AND `dialog` = :dialog LIMIT :old, :diff",
			[
				'login' => $_SESSION['user']['login'],
				'dialog' => $dialog,
				'old' => $oldquantity,
				'diff' => $diff
			]
		);
	}
}
