<?php declare(strict_types=1);

namespace frm;

// Factory -- фабрика значений, получающая аргументы-зависимости из реестра
class Factory {

	public function __construct(
		private \Closure $impl,
		private \Closure $args,
	){}

	public function __invoke(Registry $r) {
		$args = ($this->args)($r);

		return ($this->impl)(...$args);
	}
}
