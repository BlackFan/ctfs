<?php
	class Post {
		private $id;
		private $title;
		private $text;
		private $author;
		private $time;
		private $modifed;
		
		public $comments;
		
		public function __construct($database, $id, $title = '', $text = '', $author = '', $time = '', $comments = array()) {
			if(empty($title) and empty($text) and empty($author) and empty($time) and empty($comments)) {
				$id = (int)$id;
				$row = $database->singleQuery("SELECT title, text, author, time FROM posts WHERE id = $id");
				$title = $row['title'];
				$text = $row['text'];
				$author = $row['author'];
				$time = $row['time'];
				$rows = $database->query("SELECT id, text, author, time FROM comments WHERE postid = $id ORDER BY time DESC");
				$comments = array();
				foreach($rows as $comment) {
					$comments[] = new Comment($database, $comment['id'],$comment['text'],$comment['author'],$comment['time']);
				}
			}
			$this->id = $id;
			$this->title = $title;
			$this->text = $text;
			$this->author = $author;
			$this->time = $time;
			$this->comments = $comments;
			$this->modifed = FALSE;
		}
		
		public function updateInDatabase($database) {
			if($this->modifed) {
				$id = (int)$this->id;
				$title = $database->escape($this->title);
				$text = $database->escape($this->text);
				$author = $database->escape($this->author);
				$time = $database->escape($this->time);
				$database->exec("UPDATE comments SET title = '$title', text = '$text', author = '$author', time = '$time' WHERE id = $id");
				foreach($this->comments as $comment) {
					$comment->updateInDatabase($database);
				}
				$this->modifed = FALSE;
			}
		}
		
		public function getTitle() {
			return $this->title;
		}
		
		public function setTitle($title) {
			$this->title = $title;
			$this->modifed = TRUE;
		}
		
		public function getText() {
			return $this->text;
		}
		
		public function setText($text) {
			$this->text = $text;
			$this->modifed = TRUE;
		}
		
		public function getAuthor() {
			return $this->author;
		}
		
		public function setAuthor($author) {
			$this->author = $author;
			$this->modifed = TRUE;
		}
		
		public function getID() {
			return $this->id;
		}
		
		public function setID($id) {
			$this->id = $id;
			foreach($this->comments as $comment) {
				$comment->setPostID($this->id);
			}
			$this->modifed = TRUE;
		}
		
		public function getTime() {
			return $this->time;
		}
		
		public function setTime($time) {
			$this->time = $time;
			$this->modifed = TRUE;
		}
	
	}
?>