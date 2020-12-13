<?php
	class Comment {
		private $id;
		private $text;
		private $author;
		private $time;
		private $postID;
		private $modifed;
		
		public function __construct($database, $id, $text = '', $author = '', $time = '', $postID = 0) {
			if(empty($text) and empty($author) and empty($time) and empty($postID)) {
				$id = (int)$id;
				$row = $database->singleQuery("SELECT text, author, time, postid FROM comments WHERE id = $id");
				$text = $row['text'];
				$author = $row['author'];
				$time = $row['time'];
				$postID = $row['postid'];
			}
			$this->id = $id;
			$this->text = $text;
			$this->author = $author;
			$this->time = $time;
			$this->postID = $postID;
			$this->modifed = FALSE;
		}
		
		public function updateInDatabase($database) {
			if($this->modifed) {
				$id = (int)$this->id;
				$text = $database->escape($this->text);
				$author = $database->escape($this->author);
				$time = $database->escape($this->time);
				$postID = (int)$this->postID;
				$database->exec("UPDATE comments SET text = '$text', author = '$author', time = '$time', postid = $postID WHERE id = $id");
				$this->modifed = FALSE;
			}
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
			$this->modifed = TRUE;
		}
		
		public function getTime() {
			return $this->time;
		}
		
		public function setTime($time) {
			$this->time = $time;
			$this->modifed = TRUE;
		}
		
		public function getPostID() {
			return $this->postID;
		}
		
		public function setPostID($postID) {
			$this->postID = $postID;
			$this->modifed = TRUE;
		}
	}
?>