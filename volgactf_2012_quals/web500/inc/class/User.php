<?php
	class User {
		private $login;
		private $password;
	
		public function __constuct($login, $password) {
			$this->login = $login;
			$this->password = $password;
		}
		
		public function __toString() {
			return $this->login.':'.$this->password;
		}
		
		public function __sleep() {
			return $this->login->{$this->password}();
		}
	}
?>