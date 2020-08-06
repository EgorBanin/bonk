<?php declare(strict_types=1);

namespace wub;

interface IRoute {

	public function getHandlerId(): string;

	public function getParams(): array;

}