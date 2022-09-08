<?php declare(strict_types=1);

namespace bonk;

return _(function(\PDO $pdo) {
	static $repo;
	if ( ! $repo) {
		$repo = new \example\articles\Repo($pdo);
	}

	return $repo;
})->with(pdo: 'pdo.php');