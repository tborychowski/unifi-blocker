<?php

require_once 'lib/lib.php';


$devices = [
	'name' => 'macaddress'
];


$unifi = new Unifi();

$device = isset($argv[1]) ? $argv[1] : '';
$cmd = isset($argv[2]) ? $argv[2] : 'status';
$mac = isset($devices[$device]) ? $devices[$device] : null;


if (isset($mac)) {
	if ($cmd == 'status') return $unifi->block_status($mac);
	if ($cmd == 'block') return $unifi->block($mac);
	if ($cmd == 'unblock') return $unifi->unblock($mac);
}
else {
	echo 'Device not found' . PHP_EOL;
}
