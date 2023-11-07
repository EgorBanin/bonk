<?php declare(strict_types=1);

namespace frm;

/**
 * Создать билдер фарики
 * Обёртка конструктора FactoryBuilder.
 * @param \Closure $impl
 * @return FactoryBuilder
 */
function factory(\Closure $impl): FactoryBuilder
{
	return new FactoryBuilder($impl);
}

/**
 * Подключение файла с буферизацией вывода
 * @param string $file
 * @param array $params
 * @return string
 */
function ob_include(): string
{
	extract(func_get_arg(1));
	ob_start();
	require func_get_arg(0);
	return ob_get_clean();
}