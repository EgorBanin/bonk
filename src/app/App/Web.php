<?php

namespace App;

class Web  extends FrontController {
	
	protected $router;
	
	protected $tplDir;
	
	protected $params = [];
	
	protected $request;
	
	protected $response;
	
	public $user;
	
	public $db;

	public function __construct(array $routes) {
		$this->router = new Router($routes);
		$this->actionsDir = __DIR__.'/webActions';
		$this->tplDir = __DIR__.'/tpl';
		$this->response = new \Http\Response(200, [], '');
	}

	public function run($request = null) {
		if ($request === null) {
			$this->request = \Http\Request::current();
		} elseif (is_string($request)) {
			$this->request = \Http\Request::get($request);
		}
		
		$action = $this->router->route(
			parse_url($this->request->uri, PHP_URL_PATH),
			$this->params
		);
		$this->action($action);
	}
	
	protected function action($action) {
		if ($action !== false && is_file($this->actionsDir.'/'.$action)) {
			$func = require $this->actionsDir.'/'.$action;
		} else {
			$func = require $this->actionsDir.'/errors/notFound.php';
		}
		
		$this->response->headers[] = 'Content-Type: text/html; charset=UTF-8';
		$closure = $func->bindTo($this, $this);
		$result = $closure();
		
		if ($result instanceof \Http\Response) {
			$this->response = $result;
		} else {
			$this->response->body = $result;
		}
		
		$this->response->send();
	}
	
	protected function tpl($template, array $params = []) {
		return \utils\obInclude($this->tplDir.'/'.$template, $params);
	}
	
}

