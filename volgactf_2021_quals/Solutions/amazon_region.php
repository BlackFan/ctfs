<?php
	$ip = gethostbyname($argv[1]);

	$ip_ranges = json_decode(file_get_contents('https://ip-ranges.amazonaws.com/ip-ranges.json'));

	foreach ($ip_ranges->prefixes as $prefix) {
		if(isset($prefix->ip_prefix)) {
			if(ip_in_network($ip, $prefix->ip_prefix))
				print("Service $prefix->service, region $prefix->region".PHP_EOL);
		}
	}

	function ip_in_network($ip, $range) {
		list($net_addr, $net_mask) = explode('/', $range);
		if($net_mask <= 0){ return false; }
			$ip_binary_string = sprintf("%032b", ip2long($ip));
			$net_binary_string = sprintf("%032b", ip2long($net_addr));
	  return (substr_compare($ip_binary_string, $net_binary_string, 0, $net_mask) === 0);
	}
?>