<?php declare(strict_types=1);

namespace frm;

return new \example\Config([
	'db' => [
		'sqlite' => [
			'fileName' => __DIR__ . '/../data/db.sqlite'
		],
	],
]);