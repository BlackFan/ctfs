<?php

//error_reporting(0);
$host = 'localhost';
$user = 'postgres';
$pass = 'postgres';
$db = 'web400';
$bak='Z:\home\pirates\www\web400h2\projec_tor_current_tasks\carib\treasure.bak';
$bak1='Z:\home\pirates\www\web400h2\projec_tor_current_tasks\carib\index.bak';

$content3='����� ����� ����� ����� ���-����� ���<br><br><a href="http://pirate300">��������� ���</a><br><a href="http://host1">��������� �������. ����� � ���������</a><br>';
$content1='���-������ "BlackBuilding Media" � ���������� ����� � ����� ���������� ����������� ��������-������ � �� ����������� � ���� ��������.<br>
����������� ����� ������� ��������� �� ��������, ��������� �� ������������� ������� ��������.<br>
�� ���������� ��� ���������������� ������� ����� ������ ������� � ��������� � �������� � ����������� ����� ��� ����� ��������.<br>
��� ���������� �������� �� ���������� �������� ���������� � ������� ���������� ���������� ������������ ����������� � �����������.<br>';
$content2='�� ������ ����� ��� ���� ������ � ���������� ������ ������ ����� �� �������� ������ � �������� ���������� (CMS),<br> ������� �� ������������ ��� �������, ���������� �����������, ����������� ����������, ������������ � ������������ ������ � ��������� ��������.<br> �� ���������� �������� ������ �� ����� ������� �� ������������.<br>';
$content4='������. ������. VolgaCTF inc. <br>http://volgactf.ru';

pg_connect ("host=$host port=5432 dbname=$db user=$user password=$pass") or die ( "Could not connect" );
/*
pg_query ( "CREATE database web400" ) or die ( 'Error query 0' );
pg_query ( "CREATE TABLE content(id SERIAL NOT NULL, descr TEXT)" ) or die ( 'Error query 1' );
pg_query ( "CREATE TABLE administrators(login TEXT, password TEXT)" ) or die ( 'Error query 2' );
pg_query ( "INSERT INTO administrators(login,password) VALUES('CoolJohn','Iamcrazy')" ) or die ( 'Error query 3' );
pg_query ( "INSERT INTO content(descr) VALUES('$content1')" ) or die ( 'Error query 4' );
pg_query ( "INSERT INTO content(descr) VALUES('$content2')" ) or die ( 'Error query 5' );
pg_query ( "INSERT INTO content(descr) VALUES('$content3')" ) or die ( 'Error query 6' );
pg_query ( "INSERT INTO content(descr) VALUES('$content4')" ) or die ( 'Error query 7' );
*/

?>