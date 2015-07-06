<?php

namespace App;

abstract class FrontController {
	
	protected $actionDir = 'actions';
	
	abstract public function run($requests = null);

	protected function action($action) {
		$file = __DIR__.'/'.$this->actionDir.'/'.$action;
	}
	
}

