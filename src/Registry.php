<?php declare(strict_types=1);

namespace wub;

/**
 * Реестр-конструктор
 * Ассоциирует идентификаторы со скриптами, возвращающими значения.
 * Если возвращаемое значение имеет тип IValueBuilder, то вызывает его метод build.
 */
class Registry implements IRegistry {

	private array $config;

	private array $dirs;

	public function __construct(array $config, array $dirs) {
		$this->config = $config;
		$this->dirs = $dirs;
	}

	/**
	 * Получение значения по идентификатору
	 * Метод вызывает IValueBuilder::build передавая в него ссылку на самого себя.
	 * Это подразумевает неявную рекурсию.
	 * @param string $id
	 * @return mixed|null
	 * @throws \Exception
	 */
	public function get(string $id) {
		$value = null;
		foreach ($this->dirs as $ns => $baseDir) {
			if (strpos($id, $ns) !== 0) {
				continue;
			}

			try {
				$value = self::loadVal(substr($id, strlen($ns)), $baseDir);
				break;
			} catch (\Exception $e) {
				// todo
				throw $e;
			}
		}

		if ($value instanceof IValueBuilder) {
			$value = $value->build($this);
		}

		return $value;
	}

	/**
	 * Загрузка (return require $file) из директории
	 * @param string $file
	 * @param string $dir
	 * @return mixed
	 * @throws \Exception
	 */
	private static function loadVal(string $file, string $dir) {
		$baseDir = realpath($dir);
		if ($baseDir === false) {
			throw new \Exception("Директория $dir не найдена");
		}

		$path = realpath($baseDir . '/' . $file);
		if ($path === false) {
			throw new \Exception("Файл $file не найден в $dir");
		}

		$isSafe = (strpos($path, $baseDir) === 0);
		if (!$isSafe) {
			throw new \Exception("Файл $file за пределами директории $dir");
		}

		if (!is_file($path) || !is_readable($path)) {
			throw new \Exception("Файл $file не является файлом доступным для чтения");
		}

		return require $path;
	}

	public function config(string $path = null, string $separator = '.') {

		$names = isset($path)? explode($separator, $path) : [];
		$result = $this->config;
		foreach ($names as $name) {
			if (array_key_exists($name, $result)) {
				$result = $result[$name];
			} else {
				throw new \Exception('Не найден конфиг ' . $path);
			}
		}

		return $result;
	}

}