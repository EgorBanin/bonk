<?php

namespace http;

class request {
	
	use \base_type;
	
	protected $method;
	
	protected $uri;
	
	protected $body;
	
	public function __construct($method, $uri, $body) {
		$this->method = $method;
		$this->uri = $uri;
		$this->body = $body;
	}
	
}