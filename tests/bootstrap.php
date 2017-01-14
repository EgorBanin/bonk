<?php

$basePath = realpath(__DIR__.'/../src');

// настраиваем include_path
$include_path = get_include_path();
set_include_path($include_path.PATH_SEPARATOR.$basePath);

spl_autoload_register(function($className) {
	$fileName = stream_resolve_include_path(
		strtr(ltrim($className, '\\'), '\\', '/').'.php'
	);
	
	if ($fileName) {
		require_once $fileName;
	}
});
