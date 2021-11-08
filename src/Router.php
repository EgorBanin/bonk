<?php declare(strict_types=1);

namespace frm;

class Router {

	public function __construct(
		private array $config,
	) {}

	public function route(string $locator): ?Route {
		$route = null;
		foreach ($this->config as $pattern => $template) {
			$matches = [];
			$match = (preg_match($pattern, $locator, $matches) === 1);
			if ($match) {
				$params = [];
				foreach ($matches as $name => $value) {
					if (is_string($name)) {
						$params[$name] = $value;
					}
				}
				$handlerId = self::template($template, $params);
				$route = new Route($handlerId, $params);
				break;
			}
		}

		return $route;
	}

	private static function template(string $template, array $vars): string {
		$replaces = [];
		foreach ($vars as $name => $value) {
			$replaces['{' . $name . '}'] = $value;
		}

		return strtr($template, $replaces);
	}

}