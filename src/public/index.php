<?php

$appPath = realpath('../app');

// настраиваем include_path
$include_path = get_include_path();
set_include_path($include_path.PATH_SEPARATOR.$appPath);

require 'autoload.php';

$app = new \App\Web([
	'/' => 'index.php',
	'/$module' => '$module/index.php',
	'/$module/$id' => '$module/get.php',
	'/$module/$id/$action' => '$module/$action.php',
]);

// При разработке удобно определить переменную окружения,
// которая указывает на нужный конфиг.
$configFile = getenv('USER_CONFIG')?: 'config.php';
$config = require $configFile;
$mongo = new MongoClient($config['mongo']['server']);
$app->db = $mongo->selectDB('test');
$app->user = new Auth\User($config['sessionDir']);
$app->run();
