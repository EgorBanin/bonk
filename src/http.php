<?php

namespace wub;

function http_get_current_request() {
	static $current = null;
	
	if ($current) {
		return $current;
	}

	if (isset($_SERVER['REQUEST_METHOD'])) {
		$method = $_SERVER['REQUEST_METHOD'];
	} else {
		trigger_error('Can\'t find request method', \E_USER_WARNING);
		$method = 'UNKNOWN';
	}

	if (isset($_SERVER['REQUEST_URI'])) {
		$url = $_SERVER['REQUEST_URI'];
	} else {
		trigger_error('Can\'t find request uri', \E_USER_WARNING);
		$url = '/';
	}

	$headers = http_get_request_headers();

	if ($headers === false) {
		trigger_error('Can\'t find request headers', \E_USER_WARNING);
		$headers = [];
	}

	$body = file_get_contents('php://input');

	return [
		'method' => $method,
		'url' => $url,
		'headers' => $headers,
		'body' => $body
	];
}

function http_get_request_headers() {
	if (function_exists('\getallheaders')) {
		return \getallheaders();
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

function http_send_request($code, $headers, $body) {
	if (\headers_sent()) {
		\trigger_error('Headers already sent', \E_USER_WARNING);
		echo $body;

		return false;
	} else {
		\http_response_code($code);

		foreach ($headers as $header) {
			\header($header, true);
		}

		echo $body;

		return true;
	}
}

function http_redirect($url, $code = 302) {
	return http_send_request($code, ['Location: '.$url], '');
}
