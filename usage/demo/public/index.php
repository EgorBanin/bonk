<?php

set_include_path(
	get_include_path()
	.PATH_SEPARATOR.realpath(__DIR__.'/../app')
	.PATH_SEPARATOR.realpath(__DIR__.'/../../../src')
);
require 'autoload.php';

echo \Wub\Str::transform('/123/defg','/{abc:\d+}/defg', '/xxx/{abc}/yyy');
return;

$routes = [
	'/' => 'index.php',
	'/{module}' => '{module}/index.php'
];
$params = [];
foreach ($routes as $pattern => $template) {
	$action = \Wub\Str::transform('', $pattern, $template, $params);
	if ($action !== false) {
		break;
	}
}
