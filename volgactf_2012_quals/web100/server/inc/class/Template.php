<?php
	class Template {
		
		private $tplFile;
		private $content;
		
		public function __construct($tplFile) {
			$this->tplFile = $tplFile;
			if(file_exists($this->tplFile)) {
				$this->content = file_get_contents($this->tplFile);
			} else {
				if(DEBUG)
					throw new Exception('Template file not found');
				else
					die();
			}
		}
		
		public function assign($param, $value) {
			$this->content = str_replace('${'.$param.'}',$value,$this->content);
		}
		
		public function display($toString = false) {
			if($toString)
				return $this->content;
			echo $this->content;
		}
	}
?>