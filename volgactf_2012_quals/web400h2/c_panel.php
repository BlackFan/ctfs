<?php

include('header.inc');
include('webdevelopconfig.php');
$f=fopen($bak,'r');
$text=htmlspecialchars(fread($f,filesize($bak)));
$f=fopen($bak1,'r');
$text1=htmlspecialchars(fread($f,filesize($bak1)));
if (isset($_POST[login]) && isset($_POST[password])){
$login=pg_escape_string($_POST[login]);
$password=pg_escape_string($_POST[password]);
$result=pg_query ( "select * from administrators where login='$login' and password='$password'" ) or die ( 'Error query 8' );
if (( $row = pg_fetch_assoc ( $result ) )>0){
print <<< PROJ
<script>
function collapsElement(id) {
if ( document.getElementById(id).style.display != "none" ) {
        document.getElementById(id).style.display = 'none';
    }
    else {
        document.getElementById(id).style.display = '';
    }
}
</script>
<h1><center><b>Текущие проекты</b></center></h3>
<h2>tasks: </h2>
<a href="http://pirate300">Пиратский бар</a><br>
<h3><i>Комментарий: </i>Нарисовать изображения выпивки</h3><br>
<a href="http://host1">Сокровища пиратов</a><br>
<a href="javascript:collapsElement('div11')" onfocus="this.blur()">
<span id="span11">treasure.php</span>
</a>
<div style="display:none" id="div11">
<textarea ROWS = "20" COLS ="110" />
$text;
</textarea>
</div>
<br>
<a href="javascript:collapsElement('div10')" onfocus="this.blur()">
<span id="span11">index.php</span>
</a>
<div style="display:none" id="div10">
<textarea ROWS = "20" COLS ="110" />
$text1;
</textarea>
</div>
<h3><i>Комментарий: </i>Переделать функционал просмотра файлов</h3><br>
PROJ;
} else {
print <<< JS
<script>
setTimeout( 'location="index.php";', 1600 );
</script>
User not found.
JS;
}
} else {
print <<< FORM
<form action='c_panel.php' method=POST>
Login : <input type=text name=login><br>
Password : <input type=password name=password><br>
<input type=submit value='Enter'>
</form>
FORM;
}
include('footer.inc');

?>