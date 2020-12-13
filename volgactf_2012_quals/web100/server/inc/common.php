<?php
	define('DB_HOST','localhost');
	define('DB_USERNAME','root');
	define('DB_PASSWORD','');
	define('DB_BASE','volgactf_100');
	
	define('DEBUG',FALSE);
	
	define('DS',DIRECTORY_SEPARATOR);
	define('INC_DIR',MAIN_DIR.'inc'.DS);
	define('TEMPLATE_DIR',MAIN_DIR.'tpl'.DS);
	define('CLASSES_DIR',INC_DIR.'class'.DS);
	
	header('Content-type: text/html; charset=utf-8');
	
	require_once(CLASSES_DIR.'Template.php');
	require_once(CLASSES_DIR.'Database.php');
	require_once(CLASSES_DIR.'Page.php');
	require_once(CLASSES_DIR.'Post.php');
	require_once(CLASSES_DIR.'Comment.php');
	require_once(INC_DIR.'functions.php');
	
	$database = new Database(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_BASE);
?>