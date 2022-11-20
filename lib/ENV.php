<?php

/**
 * Read & parse .env file in the root of the project and load vars to $_ENV
 */
class Env {

	public static function read ($name = '.env') {
		if (isset($_SERVER['PWD'])) {
			$path = rtrim($_SERVER['PWD'], '/') . DIRECTORY_SEPARATOR . $name;
		}
		if (!isset($path) || !is_readable($path)) {
			$path = dirname($_SERVER['SCRIPT_FILENAME']) . DIRECTORY_SEPARATOR . $name;
		}
		if (!is_readable($path)) return;

		$lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		foreach ($lines as $line) {
			if (strpos(trim($line), '#') === 0) continue;

			list($name, $value) = explode('=', $line, 2);
			$name = trim($name);
			$value = trim($value);

			if (!array_key_exists($name, $_ENV)) {
				putenv(sprintf('%s=%s', $name, $value));
				$_ENV[$name] = $value;
			}
		}
	}


	public static function get ($name, $default_value = null) {
		$var = getenv($name);
		if ($var !== false) return $var;
		if (isset($default_value)) return $default_value;
		return null;
	}

}
