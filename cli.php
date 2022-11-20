<?php

require_once 'lib/_lib.php';

$unifi = new Unifi();

$cmd = isset($argv[1]) ? $argv[1] : 'list';
$mac = isset($argv[2]) ? $argv[2] : '';


if ($cmd == 'list') return $unifi->list_clients();

if (isset($mac)) {
	if ($cmd == 'status') return $unifi->block_status($mac);
	if ($cmd == 'block') return $unifi->block($mac);
	if ($cmd == 'unblock') return $unifi->unblock($mac);
}
else {
	echo 'Device or command not found' . PHP_EOL;
}
