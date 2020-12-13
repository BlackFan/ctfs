<?php
	define('URL','http://10.10.0.107/');
	define('FLAG','fb2685856c6c771189b4d3735c5a4c3e');
	define('BASE_FILE','base.php');

	include('php-webdriver/__init__.php');
	include(BASE_FILE);
	
	$flag_cookie = array("name" => "flag", 
                         "value" => FLAG,
                         "path" => "/",
                         "secure" => False);
	
	$driver = new WebDriver();
	$session = $driver->session('firefox',array('javascriptEnabled' => true));
	$i = 0;
	$prevID = 0;
	$randomPostID = 0;
	while(true) {
		echo "===\nNew iteration ".($i++)."\n";
		echo "Open main page\n";
		echo "Set flag in cookie\n";
		$session->setCookie($flag_cookie);
		if($prevID !== 0) {
			echo "Check previos post #$prevID\n";
			try {
				$session->open(URL.'post.php?id='.$prevID);
				$session->accept_alert();
			} catch (Exception $e) {
			}
		}
		echo "Random post from base id #".($randomPostID = rand(0,count($base['title'])-1))."\n";
		echo "Write new post\n";
		$session->open(URL.'addpost_483QWfSbBuo6U6pRXq8sPe0822S1QEzja.php?password=UL8SRNwwD33ivVo8i75bUag83MHs8k27B&title='.urlencode($base['title'][$randomPostID]).
			'&author='.urlencode($base['author'][$randomPostID]).'&text='.urlencode($base['text'][$randomPostID]));
		foreach($session->getAllCookies() as $sess) {
			if($sess['name'] === "prevID")
				$prevID = $sess['value'];
		}
		$session->deleteCookie("prevID");
		sleep(10);
	}
	$session->close();
?>