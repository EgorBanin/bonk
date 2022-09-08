<?php declare(strict_types=1);

namespace bonk;

/**
 * Создать билдер фарики
 * Обёртка конструктора FactoryBuilder.
 * @param \Closure $factory
 * @return FactoryBuilder
 */
function _(\Closure $factory): FactoryBuilder {
	return new FactoryBuilder($factory);
}
