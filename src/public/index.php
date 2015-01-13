<?php

$app_path = realpath(__DIR__.'/..');

// настраиваем include_path
$include_path = get_include_path();
set_include_path($include_path.PATH_SEPARATOR.$app_path);

$config = require 'config.php';

// настраиваем сессии
session_save_path($config['sessions_dir']);

// настраиваем бд
$mongo = new \MongoClient($config['mongo']['server']);
$GLOBALS['db'] = $mongo->{$config['mongo']['db']};

require_once 'includes/core.php';
require_once 'includes/router.php';

$uri = isset($_SERVER['REQUEST_URI'])? $_SERVER['REQUEST_URI'] : '';
$uri_path = parse_url($uri, PHP_URL_PATH);
$action = \router\route($uri_path, [
	'/' => 'home.php',
	'/{dir}' => '{dir}/index.php',
	'/{dir}/{file}' => '{dir}/{file}.php',
]);

if ( ! $action || ! stream_resolve_include_path('actions/'.$action)) {
	$action = 'errors/not_found.php';
}

echo \core\run('actions/'.$action);