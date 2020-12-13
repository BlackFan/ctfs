<?php
	class Password {
		private $text = '';
		
		public function __construct($text) {
			$this->text = $text;
		}
		
		public function __toString() {
			return $this->text;
		}
	}
?>