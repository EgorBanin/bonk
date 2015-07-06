<?php

namespace App;

class Web  extends FrontController {
	
	protected $router;
	
	protected $actionsDir;
	
	protected $params = [];
	
	public function __construct(array $routes) {
		$this->router = new Router($routes);
		$this->actionsDir = __DIR__.'/webActions';
	}

	public function run($request = null) {
		if ($request === null) {
			$request = \Http\Request::current();
		} elseif (is_string($request)) {
			$request = \Http\Request::get($request);
		}
		
		$action = $this->router->route(parse_url($request->uri, PHP_URL_PATH), $this->params);
		$this->action($action);
	}
	
	protected function action($action) {
		if ($action !== false && is_file($this->actionsDir.'/'.$action)) {
			$func = require $this->actionsDir.'/'.$action;
		} else {
			$func = require $this->actionsDir.'/errors/notFound.php';
		}
		
		$closure = $func->bindTo($this, $this);
		$result = $closure();
		
		$result->send();
	}
	
}

