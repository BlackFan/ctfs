<?php
	
	function pageHTML($page) {
		global $database;
		
		$page = new Page($database,$page);
		
		$return = '';
		foreach($page->posts as $post) {
			$tpl = new Template(TEMPLATE_DIR.'page_post.tpl');
			$tpl->assign('id',(int)$post->getID());
			$tpl->assign('title',htmlspecialchars($post->getTitle()));
			$tpl->assign('author',htmlspecialchars($post->getAuthor()));
			$tpl->assign('time',htmlspecialchars($post->getTime()));
			$tpl->assign('text',$post->getText());
			$return .= $tpl->display(true);
		}
		
		return $return;
	}
	
	function commentsHTML($comments) {
		$return = '';
		
		foreach($comments as $comment) {
			$tpl = new Template(TEMPLATE_DIR.'post_comment.tpl');
			$tpl->assign('time',htmlspecialchars($comment->getTime()));
			$tpl->assign('author',htmlspecialchars($comment->getAuthor()));
			$tpl->assign('text',$comment->getText());
			$return .= $tpl->display(true);
		}
		
		return $return;
	}

	function navBarHTML($page) {
		global $database;
		$count = $database->singleQuery('SELECT count(*) FROM posts');
		$count = (int)$count[0];
		$navigation = '';
		$maxpage = ceil($count / 10);
		if(($count !== NULL) and ($maxpage > 1)) {
			if($page == 0)
				$navigation .= "&lt;";
			else
				$navigation .= "<a href=\"index.php?page=$page\">&lt;</a>&nbsp;";
			for($i = 1; $i <= $maxpage; $i++) {
				if($page+1 == $i)
					$navigation .= "$i&nbsp;";
				else
					$navigation .= "<a href=\"index.php?page=$i\">$i</a>&nbsp;";
			}
			if(($page == $maxpage-1))
				$navigation .= "&gt;";
			else
				$navigation .= "<a href=\"index.php?page=".($page+2)."\">&gt;</a>&nbsp;";
		}
		return $navigation;
	}
	
	function addPost($title, $author, $text) {
		global $database;
		
		$title = $database->escape($title);
		$text = $database->escape($text);
		$author = $database->escape($author);
		$database->exec("INSERT INTO posts (id,title,text,author,time) VALUE (null,'$title','$text','$author',now())");
	}
	
	function addComment($author, $text, $postID) {
		global $database;
		
		$postID = (int)$postID;
		$database->singleQuery("SELECT id FROM posts WHERE id=$postID");
		
		$text = $database->escape($text);
		$author = $database->escape($author);
		$database->exec("INSERT INTO comments (id,author,text,time,postid) VALUE (null,'$author','$text',now(),$postID)");
	}
	
	function location($url) {
		header('Location: '.$url);
		die();
	}
?>