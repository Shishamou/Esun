<?php
/**
 * Autoloader
 *
 * @author Shisha <shisha225@gmail.com>, 20160113
 */


$composer = __DIR__ . '/../vendor/autoload.php';
if ((file_exists($composer) == is_readable($composer)) === true) {
	require $composer;
}

spl_autoload_register(function($requestClassName) {
	if (class_exists($requestClassName)) {
		return;
	}

	$root = __DIR__;

	$separatorPos = strrpos($requestClassName, '\\');
	$classNamespace = substr($requestClassName, 0, $separatorPos);
	$className = substr($requestClassName, $separatorPos + 1);

	if (( ! empty(__NAMESPACE__)) AND (strpos($classNamespace, __NAMESPACE__) === 0)) {
		$classNamespace = substr($classNamespace, strlen(__NAMESPACE__));
	}

	$requestPath = join(DIRECTORY_SEPARATOR, array_diff(array(
		$root,
		trim($classNamespace, '\\'),
		trim($className, '\\')
	), array('')));

	$requestPath = strtr($requestPath, '\\', DIRECTORY_SEPARATOR) . '.php';

	if ((file_exists($requestPath) AND is_readable($requestPath)) === true) {
		require_once $requestPath;
	}
});
