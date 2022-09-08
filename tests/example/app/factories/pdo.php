<?php declare(strict_types=1);

namespace bonk;

return _(function(\example\Config $config) {
	static $pdo;
	if ( ! $pdo) {
		$pdo = new \PDO('sqlite:' . $config->get('db.sqlite.fileName', ''));
	}

	return $pdo;
})->with(config: 'config.php');