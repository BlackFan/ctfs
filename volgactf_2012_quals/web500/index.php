<?php
	function __autoload($classname) {
		$classpath = './inc/class/'.$classname.'.php';
		if(file_exists($classpath))
			include $classpath;
	}

	require_once('inc/config.php');
	echo '<!DOCTYPE html><center><form method="POST"><input name="login" type="text"><input name="password" type="text"><br><input type="submit"></form></center>';
	if(isset($_POST['login'],$_POST['password'])) {
		// TODO
		echo underconstruction();
	} elseif(isset($_COOKIE['auth'])) {
		$auth = unserialize((string)$_COOKIE['auth']);
		if(isset($auth['login'],$auth['password'],$auth['name']) and ($auth['login'] === 'pirate') and ($auth['password'] === 'pirate1')) {
			echo "Привет, {$auth['name']}!";
		} else {
			echo "<center>Ты не пройдешь, пират!</center>";
		}
	}
?>