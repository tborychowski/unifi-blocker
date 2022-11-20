<?php

spl_autoload_register(function ($class) {
	$file = dirname(__DIR__) . '/lib/' . $class . '.php';
	if (file_exists($file)) @require_once($file);
});

Env::read();
$tz = Env::get('TZ', 'Europe/Dublin');
date_default_timezone_set($tz);


function vardump ($var, $html = true) {
	if ($html) echo '<pre>';
	echo json_encode($var, JSON_PRETTY_PRINT);
	if ($html) echo '</pre>';
}
