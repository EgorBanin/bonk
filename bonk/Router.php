<?php declare(strict_types=1);

namespace frm;

/**
 * Роутер
 * Очень простой роутер, который сопоставляет локатор запроса c регулярными выражениями.
 */
class Router
{

	public function __construct(
		private array $config,
		private string $lTpl = '{',
		private string $rTpl = '}',
	) {}

	/**
	 *
	 * @param string $locator
	 * @return array [$handlerId, $params]
	 * @throws Exception
	 */
	public function route(string $locator): array
	{
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

				return [$handlerId, $params];
			}
		}

		throw Exception::system(sprintf('route not found for %s', $locator));
	}

	private function template(string $template, array $vars): string
	{
		$replaces = [];
		foreach ($vars as $name => $value) {
			$replaces[$this->lTpl . $name . $this->rTpl] = $value;
		}

		return strtr($template, $replaces);
	}

}