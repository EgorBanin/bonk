<?php declare(strict_types=1);

namespace example;

class Config {
	public function __construct(
		private array $config,
	) {}

	public function get(string $name, $default, string $separator = '.') {
		$path = explode($separator, $name);
		$result = $this->config;
		foreach ($path as $k) {
			if (array_key_exists($k, $result)) {
				$result = $result[$k];
				continue;
			} else {
				$result = $default;
				break;
			}
		}

		return $result;
	}
}