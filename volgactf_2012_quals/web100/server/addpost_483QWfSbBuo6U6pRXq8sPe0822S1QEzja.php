<?php
	define('MAIN_DIR',getcwd().'/');
	require_once(MAIN_DIR.'inc/common.php');
	if(isset($_GET['password']) and $_GET['password'] == 'UL8SRNwwD33ivVo8i75bUag83MHs8k27B') {
		if(isset($_GET['title'],$_GET['author'],$_GET['text'])) {
			$_GET['text'] = str_replace(array("\r\n","\n"),'<br>',$_GET['text']);
			addPost($_GET['title'],$_GET['author'],$_GET['text']);
			$id = $database->getLastInsertedID();
		}
		
		SetCookie("prevID",$id);
	}
	require_once(INC_DIR.'common_end.php');
?>