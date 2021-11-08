<?php declare(strict_types=1);

namespace wub;

/**
 * Реестр
 */
interface IRegistry {

	/**
	 * Получение значения по идентификатору
	 * @param string $id
	 * @return mixed
	 */
	public function get(string $id);

	/**
	 * Получение значения параметра конфигурации
	 * @param string|null $path путь к значению, наприер db.host
	 * @param string $separator разделитель ключей в пути
	 * @return mixed
	 */
	public function config(string $path = null, string $separator = '.');

}