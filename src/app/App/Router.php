<?php

namespace App;

/**
 * Простой маршрутизатор
 */
class Router {
	
	const VAR_MARKER = '$';
	
	protected $routes = [];
	
	public function __construct(array $routes) {
		$this->routes = $routes;
	}
	
	/**
	 * Прорверка совпадения с шаблоном
	 * @param string $path проверяемый uri-путь
	 * @param string $pattern шаблон
	 * @return array|false
	 */
	public static function match($path, $pattern) {
		$patternArr = explode('/', trim($pattern, '/'));
		$pathArr = explode('/', trim($path, '/'));
		$length = count($patternArr);
		
		if ($length !== count($pathArr)) {
			return false;
		}
		
		$matches = [];
		for ($i = 0; $i < $length; ++$i) {
			if (strpos($patternArr[$i], self::VAR_MARKER) === 0) {
				$matches[substr($patternArr[$i], 1)] = $pathArr[$i];
			} elseif ($patternArr[$i] !== $pathArr[$i]) {
				return false;
			}
		}
		
		return $matches;
	}
	
	/**
	 * Подстановка переменных в строку-шаблон
	 * @param string $template
	 * @param array $params
	 * @return string
	 */
	public static function replace($template, array $params) {
		$replaces = [];
		foreach ($params as $name => $value) {
			$replaces[self::VAR_MARKER.$name] = $value;
		}

		return strtr($template, $replaces);
	}
	
	/**
	 * Поиск подходящего маршрута
	 * @param string $path
	 * @param array $params
	 * @return string|false
	 */
	public function route($path, array &$params = []) {
		foreach ($this->routes as $pattern => $template) {
			if (is_string($template)) {
				$matches = self::match($path, $pattern);

				if ($matches !== false) {
					$params += $matches;
					return self::replace($template, $params);
				}
			} elseif (is_callable($template)) {
				$result =  $template($path, $params);
				
				if ($result !== false) {
					return $result;
				}
			} else {
				continue;
			}
		}
		
		return false;
	}
	
}
