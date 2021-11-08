<?php declare(strict_types=1);

namespace frm;

// Make -- билдер для Factory
// Нужен исключительно для эстетических целей: `make(fn($foo) => $foo->bar())->with(foo: '/foo')'`.
class Make {

	public function __construct(
		private \Closure $factory,
	) {}

	public function with(...$args): Factory {
		return new Factory(
			$this->factory,
			fn(Registry $r) => array_map(fn($a) => $r->get($a), $args),
		);
	}

	public function through(\Closure $args): Factory {
		return new Factory(
			$this->factory,
			$args,
		);
	}

}