<?php

namespace App;

abstract class FrontController {
	
	protected $actionDir;
	
	abstract public function run($requests = null);
	
}

