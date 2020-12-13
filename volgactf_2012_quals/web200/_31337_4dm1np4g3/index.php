<?php
			$message = '';
			if(isset($_POST['username'],$_POST['password'])) {
					if($_POST['username'] !== 'admin' or $_POST['password'] !== 'xS0oNE8672u3e7RRAQh4VQ4xWyvi') {
						header('Location: index.php?error=1');
					}
					$message =  '<br><b>You did it, flag - 5f5910be6322db20df7bc94c66e4703d</b>';
				}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WEB 200</title>
<link href="tpl_style.css" rel="stylesheet" type="text/css" />

</head>
<body>

<div id="tpl_wrapper">

	<span class="bg_top"></span>
    <span class="bg_bottom"></span>

	<div id="tpl_menu">
        <ul>
          <li class="last"><a href="index.php" class="current">Admin Page</a></li>
        </ul>
	</div>
    
    <div id="tpl_header">
		<div id="site_title"></div>
	</div>
    
    <div id="tpl_main">
    	<div class="cbox_fw">
        	<div id="member_login">
            
            	<h3>Admin Login</h3>
                
            	<form action="index.php" method="post">
                
                 	<label>Username</label><input name="username" type="text" class="txt_field" id="name" title="name" value="" size="10" maxlength="30" />
                	<div class="cleaner h10"></div>
					<label>Password</label><input name="password" type="password" class="txt_field" id="password" title="password" value="" size="10" maxlength="30" />
<div class="cleaner h10"></div>
					<input type="submit" name="login" value="" alt="login" id="login" title="Search" class="sub_btn"  />
                    
                </form>
                
            </div>
			<?php 
				if(isset($_GET['error'])) 
					echo '<br><b>Incorrect login/passord</b>'; 
				echo $message;
			?> 
        </div>

        <div id="content">
        	<div class="cbox_fws">
			</div>
        </div>
        
        <div class="cleaner"></div>
    </div> 
    <div id="tpl_main_bottom"></div>
    
    <div id="tpl_footer">
        Copyright &copy; 2048 <a href="http://volgactf.ru">VolgaCTF</a>
        
        <div class="cleaner"></div>
    </div>

</div> 

</body>
</html>