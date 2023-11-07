<?php declare(strict_types=1);

namespace frm;

/**
 * Билдер для Factory
 * Нужен исключительно для эстетических целей: `factory(fn($foo) => $foo->bar())->with(foo: '/foo')'`.
 */
class FactoryBuilder
{

	public function __construct(
		private \Closure $factory,
	) {}

	/**
	 * Собирает фабрику
	 * Использует Registry, чтобы разрешить зависимости.
	 * @param ...$args string список идентификаторов зависимостей
	 * @return Factory
	 */
	public function with(...$args): Factory
	{
		return $this->by(fn(Registry $r) => array_map(fn($a) => $r->get($a), $args));
	}

	/**
	 * Собирает фабрику с конкретной функцией получения аргументов
	 * @param \Closure $args
	 * @return Factory
	 */
	public function by(\Closure $args): Factory
	{
		return new Factory(
			$this->factory,
			$args,
		);
	}

}