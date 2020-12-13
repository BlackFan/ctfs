<?php
	define('MAIN_DIR',getcwd().'/');
	require_once(MAIN_DIR.'inc/common.php');
	
	$postID = isset($_GET['id']) ? (int)$_GET['id'] : 1;
	
	$post = new Post($database, $postID);
	
	if(isset($_POST['author'],$_POST['text'],$_POST['id']) and !empty($_POST['author']) and !empty($_POST['text']) and !empty($_POST['id'])) {
		addComment((string)$_POST['author'],(string)$_POST['text'],(int)$_POST['id']);
		location('post.php?id='.$postID);
	}
	
	$tpl = new Template(TEMPLATE_DIR.'post.tpl');
	
	$tpl->assign('id',(int)$post->getID());
	$tpl->assign('title',htmlspecialchars($post->getTitle()));
	$tpl->assign('time',htmlspecialchars($post->getTime()));
	$tpl->assign('author',htmlspecialchars($post->getAuthor()));
	$tpl->assign('text',$post->getText());
	$tpl->assign('comments',commentsHTML($post->comments));
	
	$tpl->display();
	
	require_once(INC_DIR.'common_end.php');
?>