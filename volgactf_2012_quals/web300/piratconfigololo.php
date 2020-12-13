<?php

$host = "localhost";
$user = "volgactf_web300";
$pass = "3a4U4mQU";
$db = "web300";

define ( 'KEY', 'elpirata' );
define ( 'FLAG', '14f5f813873f79abd25eeec00a084583' );

mysql_connect ( $host, $user, $pass ) or die ( "Could not connect: " . mysql_error () );
mysql_select_db ( $db ) or die ( "Could not select database: " . mysql_error () );

mysql_query ( "CREATE TABLE IF NOT EXISTS key_(key300 VARCHAR(20))" ) or die ( mysql_error () );
mysql_query ( "CREATE TABLE IF NOT EXISTS ua(id INT AUTO_INCREMENT PRIMARY KEY, useragent TEXT)" ) or die ( mysql_error () );
mysql_query ( "INSERT INTO key_(key300) VALUES('".KEY."')" ) or die ( mysql_error () );

?>