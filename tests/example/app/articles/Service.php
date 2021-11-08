<?php declare(strict_types=1);

namespace example\articles;

class Service {
	public function __construct(
		private Repo $repo,
	) {}

	public function top(): array {
		return $this->repo->select();
	}
}