<?php declare(strict_types=1);

namespace frm;

/**
 * Фабрика значения, получающая аргументы-зависимости из реестра
 */
class Factory {

	public function __construct(
		private \Closure $impl,
		private ?\Closure $args,
	){}

	public function __invoke(Registry $r) {
		if ($this->args) {
			$args = ($this->args)($r);
		} else {
			$args = [];
		}

		return ($this->impl)(...$args);
	}
}
