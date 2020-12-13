<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Treasure Hunters</title>
<link rel="stylesheet" type="text/css" href="style.css" />
<script type="text/javascript" src="unitpngfix.js"></script>
</head>
<body>

<div id="main_container">
	<div class="top_leafs"><img src="images/top_leafs.png" alt="" title=" "/></div>
	<div id="header">
    	<div class="logo">
       <a href="index.html">Pirate Treasure</a>
        </div>
        <div class="menu">
        	<ul>                            
        	<li class="selected"><a href="index.php">Главная</a></li>
                <li><a href="islands.php">Острова</a></li>
				<li><a href="legends.php">Легенды</a></li>
                <li><a href="maps.php">Карты</a></li>
				 <li><a href="treasure.php">Сокровища</a></li>
        	</ul>
        </div>
    </div>
    <div id="center_content">
                     <br><br><center><h1><b>В разработке<br>Under Construction</b></h1></center>
					 <?php if(isset($_GET[read]) && isset($_GET[page])){
					 $page='Z:\home\pirates\www\web400\\'.$_GET[page];
	$f=fopen($page,'r');
print fread($f,filesize($page));
}
?>
                    
            
                           <div class="bottom_content">
                <center><div class="bottom_right">
                   Created by <a href="http://85.113.37.68:31337/web400h2/" target="_blank">BlackBuilding Media</a>&copy; Pirate Treasure 2012
                </div></center>
            </div>

    </div>

</div>
<!-- end of main_container -->

</body>
</html>