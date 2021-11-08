<?php declare(strict_types=1);

namespace frm;

class Route {
	public function __construct(
		public string $handlerId,
		public array $params,
	){}
}