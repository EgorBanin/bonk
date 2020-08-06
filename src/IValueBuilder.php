<?php declare(strict_types=1);

namespace wub;

/**
 * Построитель значения
 * Строит значение используя реестр для удовлетворения зависимостей.
 */
interface IValueBuilder {

	public function build(IRegistry $registry);

}