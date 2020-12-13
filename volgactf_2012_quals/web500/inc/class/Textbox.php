<?php
	class Textbox {
		private $obj;
		
		public function __construct($obj) {
			$this->obj = $obj;
		}
		
		function __toString() {
			return $this->obj->printObj();
		}
	}
?>