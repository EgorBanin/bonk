<?php declare(strict_types=1);

namespace wub;

class Router implements IRouter {

	private array $config;

	public function __construct(array $config) {
		$this->config = $config;
	}

	public function route(string $locator): ?IRoute {
		$params = [];
		$pattern = \func_all\arr_usearch($this->config, function ($pattern) use ($locator, &$params) {
			$matches = [];
			$match = (preg_match($pattern, $locator, $matches) === 1);
			if ($match) {
				foreach ($matches as $name => $value) {
					if (is_string($name)) {
						$params[$name] = $value;
					}
				}
			}

			return $match;
		});

		if ($pattern !== false) {
			$handlerId = \func_all\str_template($this->config[$pattern], $params);

			return new Route($handlerId, $params);
		} else {
			return null;
		}
	}

}