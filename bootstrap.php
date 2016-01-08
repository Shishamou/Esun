<?php

spl_autoload_register(function($class) {
	if (strpos($class, __NAMESPACE__ . '\\') === 0) return;

	$root = __DIR__;
	$name = substr($class, strlen(__NAMESPACE__));
	$name = strtr(trim($name, '\\'), '\\', DIRECTORY_SEPARATOR);

	$path = "{$root}/{$name}.php";

	if ((file_exists($path) == is_readable($path)) !== true) {
		throw new Exception("File not found : {$path}", 1);
	}

	require $path;
});
