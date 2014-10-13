<?php

namespace http;

class response {
	
	use \base_type;
	
	protected $headers = [];
	
	protected $body;
	
	public function __construct(array $headers, $body) {
		$this->headers = $headers;
		$this->body = $body;
	}
	
}