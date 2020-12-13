<?php
	class Database {
	
		private $link;
	
		public function __construct($server,$username,$password,$database) {
			if($this->link = mysql_connect($server,$username,$password)) {
				mysql_select_db($database,$this->link);
			} else {
				if(DEBUG)
					throw new Exception('Cannot connet to database');
				else
					die();
			}
		}
		
		public function __destruct() {
			if(is_resource($this->link)) {
				mysql_close($this->link);
				$this->link = null;
			}
		}
		
		public function close() {
			$this->__destruct();
		}
		
		public function singleQuery($query) {
			$result = mysql_query($query,$this->link);
			if($result) {
				if(mysql_num_rows($result) !== 1) {
					if(DEBUG)
						throw new Exception('Error in singleQuery');
					else
						die();
				}
				$return = mysql_fetch_array($result);
				mysql_free_result($result);
				return $return;
			} else {
				if(DEBUG)
					throw new Exception('Error in singleQuery');
				else
					die();
			}
		}
		
		public function query($query) {
			$result = mysql_query($query,$this->link);
			if($result) {
				$return  = array();
				while($row = mysql_fetch_array($result)) {
					$return[] = $row;
				}
				mysql_free_result($result);
				return $return;
			} else {
				if(DEBUG)
					throw new Exception('Error in query');
				else
					die();
			}
		}
		
		public function exec($query) {
			$result = mysql_query($query,$this->link);
			if($result) {
				return TRUE;
			} else {
				if(DEBUG)
					throw new Exception('Error in exec');
				else
					die();
			}
		}
		
		public function getLastInsertedID() {
			$return = $this->singleQuery('SELECT LAST_INSERT_ID()');
			return $return[0];
		}
		
		public function escape($string) {
			return mysql_real_escape_string($string,$this->link);
		}
		
	}
?>