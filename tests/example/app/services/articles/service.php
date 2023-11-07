<?php declare(strict_types=1);

namespace frm;

return factory(function(\example\articles\Repo $repo) {
	return new \example\articles\Service($repo);
})->with(repo: 'articles/repo.php');