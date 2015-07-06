<?php

namespace Http;

class Response {
    
    public $status;
    
    public $headers;
	
    public $body;
    
    public function __construct($status, array $headers, $body) {
        $this->status = $status;
        $this->headers = $headers;
        $this->body = $body;
    }
	
	public function send() {
		if (headers_sent()) {
			\trigger_error('Headers already sent', \E_USER_WARNING);
			echo $this->body;
			
			return false;
		} else {
			http_response_code($this->status);
			
			foreach ($this->headers as $header) {
                header($header, true);
            }
			
			echo $this->body;
			
			return true;
		}
	}
    
}

