<?php

//error_reporting(0);
$host = 'localhost';
$user = 'postgres';
$pass = 'postgres';
$db = 'web400';
$bak='Z:\home\pirates\www\web400h2\projec_tor_current_tasks\carib\treasure.bak';
$bak1='Z:\home\pirates\www\web400h2\projec_tor_current_tasks\carib\index.bak';

$content3='Среди наших работ такие веб-сайты как<br><br><a href="http://pirate300">Пиратский бар</a><br><a href="http://host1">Карибские острова. Клады и сокровища</a><br>';
$content1='Веб-студия "BlackBuilding Media" — признанный лидер в сфере разработки эффективных Интернет-сайтов и их продвижения в сети Интернет.<br>
Большинство наших заказов поступает от компаний, пришедших по рекомендациям прежних клиентов.<br>
Мы предлагаем Вам профессиональное решение задач Вашего бизнеса в Интернете — создание и продвижение сайта для Вашей компании.<br>
Для реализации проектов мы используем новейшие достижения в области технологий разработки программного обеспечения и мультимедиа.<br>';
$content2='Мы делаем сайты для всей России и предлагаем полный спектр услуг по созданию сайтов с системой управления (CMS),<br> начиная от изготовления веб дизайна, заканчивая размещением, технической поддержкой, оптимизацией и продвижением сайтов в поисковых системах.<br> Мы предлагаем создание сайтов от самых простых до эксклюзивных.<br>';
$content4='Россия. Самара. VolgaCTF inc. <br>http://volgactf.ru';

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