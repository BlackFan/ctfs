<?php
	class Object implements Serializable {
		private $data;
		
		function __construct($data) {
			$this->data = $data;
		}
	
		public function serialize() {
			return serialize($this->data);
		}
		
		public function unserialize($s) {
			$this->data = unserialize($s);
		}
		
		public function printObj() {
			return serialize($this);
		}
	}
?>