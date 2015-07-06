<?php

namespace Http;

class Request {
	
	public $method;
	
	public $uri;
	
	public $headers;
	
	public $body;
	
	private static $current = null;
	
	public function __construct($method, $uri, array $headers, $body) {
		$this->method = $method;
		$this->uri = $uri;
		$this->headers = $headers;
		$this->body = $body;
	}
	
	public static function current() {
		if (self::$current) {
			return self::$current;
		}
		
		if (isset($_SERVER['REQUEST_METHOD'])) {
			$method = $_SERVER['REQUEST_METHOD'];
		} else {
			trigger_error('Can\'t find request method', \E_USER_WARNING);
			$method = 'UNKNOWN';
		}
		
		if (isset($_SERVER['REQUEST_URI'])) {
			$uri = $_SERVER['REQUEST_URI'];
		} else {
			trigger_error('Can\'t find request uri', \E_USER_WARNING);
			$uri = '/';
		}
		
		$headers = self::getAllHeaders();
		
		if ($headers === false) {
			trigger_error('Can\'t find request headers', \E_USER_WARNING);
			$headers = [];
		}
		
		$body = file_get_contents('php://input');
		
		return new self($method, $uri, $headers, $body);
	}
	
	public static function get($uri, array $headers = []) {
		return new self('GET', $uri, $headers, '');
	}
    
    public static function getAllHeaders() {
        if (function_exists('getallheaders')) {
            return getallheaders();
        } else {
            $headers = [];
            foreach ($_SERVER as $key => $value) {
                if (strpos($key, 'HTTP_') === 0) {
                    $name = implode('-', array_map(function($v) {
                        return ucfirst(strtolower($v));
                    }, explode('_', substr($key, 5))));
                    $headers[$name] = $value;
                }
            }
            return $headers;
        }
    }
	
}
