<?php declare(strict_types=1);

namespace frm;

// Registry -- реестр зависимостей
class Registry {
	private array $dirs;

	// @throws Err
	public function __construct(array $dirs) {
		foreach ($dirs as $ns => $dir) {
			$rDir = realpath($dir);
			if ( ! $rDir) {
				throw new Err("$dir not found", Err::CODE_SYSTEM);
			}
			$this->dirs[$ns] = $rDir;
		}
	}

	// @throws Err
	public function get(string $id) {
		$factory = null;
		foreach ($this->dirs as $ns => $baseDir) {
			if ( ! str_starts_with($id, $ns)) {
				continue;
			}

			$factory = self::loadFactory(substr($id, strlen($ns)), $baseDir);
			break;
		}

		if ( ! $factory) {
			throw new Err("factory was not found for $id");
		}

		return $factory($this);
	}

	// loadFactory -- загрузка фабрики (return require $file) из директории
	// @throws Err
	private static function loadFactory(string $file, string $dir) {
		$path = realpath($dir . '/' . $file);
		if ( ! $path) {
			throw new Err("file $file not found in $dir directory", Err::CODE_SYSTEM);
		}

		$isSafe = (strpos($path, $dir) === 0);
		if ( ! $isSafe) {
			throw new Err("file $file is not in $dir", Err::CODE_SYSTEM);
		}

		if (is_file($path) && is_readable($path)) {
			try {
				$result = require $path;
			} catch (\Throwable $t) {
				throw new Err($t->getMessage(), Err::CODE_SYSTEM, $t);
			}
		} else {
			throw new Err("file $file is not readable", Err::CODE_SYSTEM);
		}

		return $result;
	}
}