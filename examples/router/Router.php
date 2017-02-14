<?php

namespace examples;

require_once __DIR__.'/../wub.php';

/**
 * Классный роутер для вашего веб-приложения
 */
class Router {
	
	private $routes;
	
	public function __construct($routes) {
		$this->routes = $routes;
	}
	
	public function route($url) {
		$params = [];
		$pattern = arr_usearch($this->routes, function($pattern) use($url, &$params) {
			$matches = [];
			if (preg_match($pattern, $url, $matches) === 1) {
				foreach ($matches as $name => $value) {
					if ( ! ctype_digit((string) $name)) {
						$params[$name] = $value;
					}
				}

				return $pattern;
			}
		});
		
		if ($pattern !== false) {
			$actionFile = str_template($this->routes[$pattern], $params);
			
			if (is_readable($actionFile)) {
				$action = require $actionFile;
				
				return [$action, $params];
			}
		}
		
		return false;
	}
}

$router = new Router([
	'~^/(?<controllerName>[^/]+)$~' => __DIR__.'/{controllerName}Controller.php',
]);

$bad = $router->route('/bad');
DEBUG($bad); // false
$my = $router->route('/my');
call_user_func($my[0], $my[1]);
