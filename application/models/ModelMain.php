<?php
namespace application\models;
use application\core\model;

class ModelMain extends Model
{
	public function _default($params = null)
	{
		$_GET['page'] = $_GET['page'] ?? 1;
		$from = $_GET['page'] * $params - $params;
		return $this->db->fetch_all("SELECT
				`news`.`id`, 
				`news`.`title`, 
				`news`.`tags`, 
				`news`.`preview_img`, 
				`news`.`description`, 
				`news`.`date`, (SELECT COUNT(*) FROM comments WHERE `comments`.`post_id`=`news`.`id`) AS `comments`
				FROM `news` ORDER BY `news`.`date` DESC
				LIMIT ".$from.", ".$params."
			", null);
	}
	
	public function getPost($id)
	{
		if($this->db->get_rowCount("SELECT * FROM `news` WHERE `id` = :id;", ['id' => $id]))
		{
			return ['content' => $this->db->fetch("SELECT * FROM `news` WHERE `id` = :id;", ['id' => $id]),
					'comments' => $this->getComments(['post_id' => $id])];
		}
	}
	
	public function addComment($params)
	{
		$params['comment'] = preg_replace('/(\s{2,})|(&nbsp;)/', '', $params['comment']);
		$this->db->execute_query(
			"INSERT INTO `comments` (`id`, `post_id`, `author`, `comment`, `date`) 
			VALUES (NULL, :post_id, :author, :comment, NOW());", 
			[
				"post_id" => $params['post_id'],
				"author" => $params['author'],
				"comment" => preg_replace('/<(\w+)\b(?:\s+[\w\-.:]+(?:\s*=\s*(?:"[^"]*"|"[^"]*"|[\w\-.:]+))?)*\s*\/?>((\s*)|(<br>))<\/\1\s*>/', '', $params['comment'])
			]
		);
	}
	
	public function validationComment($comment)
	{
		$comment = strip_tags(preg_replace('/(&nbsp;)|(<div>(\s+)<\/div>)|(<div><br><\/div>)|(\s+)/', ' ', $comment));
		if(strlen($comment) >= 5 && strlen($comment) <= 500)
			return $comment;
		return false;
	}
	
	public function deleteComment($params)
	{
		$this->db->execute_query(
			"DELETE FROM `comments` WHERE `comments`.`id` = :id", 
			["id" => $params['id']]
		);
	}
	
	public function getComments($params)
	{
		return $this->db->fetch_all("SELECT
					`comments`.`id`,
					`comments`.`author`,
					`comments`.`comment`,
					`comments`.`date`,
					`users`.`id` AS `user_id`,
					`users`.`image` AS `image`
					FROM `comments` INNER JOIN `users` ON `comments`.`author`=`users`.`login`
					AND `comments`.`post_id`= :post_id
					ORDER BY `comments`.`date` ASC",
					['post_id' => $params['post_id']]);
	}
	
	public function getPostsCount()
	{
		return $this->db->get_rowCount("SELECT * FROM `news` WHERE 1");
	}
}
