<?php declare(strict_types=1);

namespace bonk;

/**
 * Реестр зависимостей
 */
class Registry {
	private array $dirs;

    /**
     * @param array $dirs
     * @throws Exception
     */
	public function __construct(array $dirs) {
		foreach ($dirs as $ns => $dir) {
			$rDir = realpath($dir);
			if ( ! $rDir) {
                throw Exception::system("$dir not found");
			}
			$this->dirs[$ns] = $rDir;
		}
	}

    /**
     * Получение зависимости
     *
     * @param string $id
     * @return mixed
     * @throws Exception
     */
	public function get(string $id) {
		$factory = null;
		foreach ($this->dirs as $ns => $baseDir) {
			if ( ! str_starts_with($id, $ns)) {
				continue;
			}

            try {
                $factory = self::loadFactory(substr($id, strlen($ns)), $baseDir);
            } catch (Exception $e) {
                throw Exception::system("can't load factory $id: $e");
            }
			break;
		}

		if ( ! $factory) {
			throw Exception::system("factory not found for $id");
		}

		return $factory($this);
	}

    /**
     * Загрузка фабрики (return require $file) из директории
     *
     * @param string $file
     * @param string $dir
     * @return mixed
     * @throws Exception
     */
	private static function loadFactory(string $file, string $dir) {
		$path = realpath($dir . '/' . $file);
		if ( ! $path) {
			throw Exception::system("file $file not found in $dir directory");
		}

		$isSafe = (str_starts_with($path, $dir));
		if ( ! $isSafe) {
			throw Exception::system("file $file is not in $dir");
		}

		if (is_file($path) && is_readable($path)) {
			try {
				$result = require $path;
			} catch (\Throwable $t) {
				throw Exception::system("error on factory require: " . $t->getMessage(), $t);
			}
		} else {
			throw Exception::system("file $file is not readable");
		}

		return $result;
	}
}