<?php declare(strict_types=1);

namespace wub;

/**
 * Построитель значения
 * Состоит из функции-фабрики, строящей значение, и функции возвращающей
 * аргументы для функции-фабрики.
 *
 * <code>
 * return ValueBuilder(function($fileName) {
 * 	static $db; // использование static позволяет инстанцировать объект только один раз
 * 	if ($db === null) {
 * 		$db = new \PDO('sqlite:' . $fileName);
 * 	}
 * 	return $db;
 * })->args(function(IRegistry $registry) {
 * 	return [$registry->config('db.fileName')];
 * });
 * </code>
 */
class ValueBuilder implements IValueBuilder {

	private $impl;

	private $args;

	public function __construct(callable $impl, $args = null) {
		$this->impl = $impl;
		$this->args = $args;
	}

	/**
	 * Статический канструктор
	 * Сахар. Позволяет сконфигурировать билдер вызовами ValueBuilder::impl(...)->args(...).
	 * @param callable $impl функция-фабрика
	 * @return self
	 */
	public static function impl(callable $impl): self {
		return new self($impl, []);
	}

	/**
	 * Сеттер аргументов для функции-фабрики
	 * В общем случае представляет из себя функцию, принимающую на вход IRegister
	 * и возвращающую значения аргументов. Но может быть строкой или массивом строк.
	 * В таком случае строки будут интерпретироваться как id значений из реестра.
	 * @param mixed $args
	 * @return $this
	 */
	public function args($args): self {
		$this->args = $args;

		return $this;
	}

	public function build(IRegistry $registry) {
		$args = $this->buildArgs($registry);
		$value = ($this->impl)(...$args);
		// todo: обработка ошибок

		return $value;
	}

	private function buildArgs(IRegistry $registry): array {
		if ($this->args === null) {
			return [];
		}

		if (is_callable($this->args)) {
			$args = ($this->args)($registry);
		} else {
			$serviceIds = (array)$this->args;
			$args = [];
			foreach ($serviceIds as $id) {
				$args[] = $registry->get((string) $id);
			}
		}

		return $args;
	}

}