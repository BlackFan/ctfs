<?php
	class Page {
	
		const POST_PER_PAGE = 10;
	
		private $id;
		
		public $posts;
	
		public function __construct($database, $id) {
			$id = (int)$id;
			$this->posts = array();
			$posts = $database->query("SELECT id, title, text, author, time FROM posts ORDER BY time DESC LIMIT ".(($id-1)*$this::POST_PER_PAGE).",".$this::POST_PER_PAGE);
			if(count($posts) == 0) die();
			foreach($posts as $post) {
				$this->posts[] = new Post($database,$post['id'],$post['title'],$post['text'],$post['author'],$post['time'],array());
			}
		}
	}
?>