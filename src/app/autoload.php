<?php

spl_autoload_register(function($className) {
	$fileName = stream_resolve_include_path(
		strtr(ltrim($className, '\\'), '\\', '/').'.php'
	);
	
	if ($fileName) {
		require_once $fileName;
	}
});

require_once 'utils.php';