<?php declare(strict_types=1);

namespace bonk;

return function() {
	static $config;
	if ( ! $config) {
		$config = new \example\Config([
			'db' => [
				'sqlite' => [
					'fileName' => __DIR__ . '/../data/db.sqlite'
				],
			],
		]);
	}

	return $config;
};