<?php

namespace Http;

class Response {
    
    public $code;
    
    public $headers;
	
    public $body;
    
    public function __construct($code, array $headers, $body) {
        $this->code = $code;
        $this->headers = $headers;
        $this->body = $body;
    }
	
	public function send() {
		if (headers_sent()) {
			\trigger_error('Headers already sent', \E_USER_WARNING);
			echo $this->body;
			
			return false;
		} else {
			http_response_code($this->code);
			
			foreach ($this->headers as $header) {
                header($header, true);
            }
			
			echo $this->body;
			
			return true;
		}
	}
	
	public static function redirect($uri, $code = 302) {
		return new self($code, ['Location: '.$uri]);
	}
    
}

