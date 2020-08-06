<?php declare(strict_types=1);

namespace wub;

interface IRouter {

	public function route(string $locator): ?IRoute;

}