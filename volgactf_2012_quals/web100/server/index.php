<?php
	define('MAIN_DIR',getcwd().'/');
	require_once(MAIN_DIR.'inc/common.php');
	
	$pageID = isset($_GET['page']) ? (int)$_GET['page'] : 1;
	
	$tpl = new Template(TEMPLATE_DIR.'index.tpl');
	$tpl->assign('posts',pageHTML($pageID));
	$tpl->assign('navbar',navBarHTML($pageID-1));
	$tpl->display();
	require_once(INC_DIR.'common_end.php');
?>