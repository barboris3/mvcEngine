<?php
namespace application\lib;

class Pagination
{
	private $max;
	private $total;
	private $amout;
	
	public function __construct($total, $max) 
	{
		$this->total = $total;
		$this->max = $max;
		$this->amout = $this->set_pages();
	}
	
	private function set_pages()
	{
		return $this->total && $this->max ? ceil($this->total / $this->max) : 0;
	}
	
	public function get_pages()
	{
		if($this->amout > 7) {
			$current = $_GET['page'];
			$pages[] = 1;
			if($current - 1 > 2) {
				$pages[] = '...'; }
			if($current - 1 > 1) {
				$pages[] = $current - 1; }
			if($current < $this->amout && $current > 1) {
				$pages[] = $current; }
			if($current + 1 < $this->amout) {
				$pages[] = $current + 1; }
			if($current + 1 < $this->amout - 1) {
				$pages[] = '...'; }
			$pages[] = $this->amout;
		} else {
			for($i = 1; $i <= $this->amout; $i++) {
				$pages[] = $i;
			}
		}
		return $pages;
	}
}