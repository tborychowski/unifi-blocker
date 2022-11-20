<?php

require_once 'lib/_lib.php';

$unifi = new Unifi();

$cmd = isset($argv[1]) ? $argv[1] : 'list';
$mac = isset($argv[2]) ? $argv[2] : '';


// non-device commands
if ($cmd == 'list') return $unifi->list_clients();

if (empty($mac)) {
	echo 'Command not found' . PHP_EOL;
	exit(1);
}

// device commands
if ($cmd == 'status') return $unifi->block_status($mac);
if ($cmd == 'block') return $unifi->block($mac);
if ($cmd == 'unblock') return $unifi->unblock($mac);
