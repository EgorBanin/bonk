<?php

namespace wub;

/**
 * Получить текущий запрос
 * Заголовки получаются с помощью io_get_request_headers.
 * Имена заголовков приводятся к виду Имя-Заголовка. Будте внимательны,
 * при использовании CamelCase или аббривиатур, буквы в верхнем регистре
 * в середине слова будут приведены к нижнему.
 * @return array {method, url, headers, body}
 */
function io_get_request() {
	static $current = null;

	if ($current) {
		return $current;
	}

	if (isset($_SERVER['SERVER_PROTOCOL'])) {
		$protocol = $_SERVER['SERVER_PROTOCOL'];
	} else {
		trigger_error('Can\'t find server protocol', \E_USER_WARNING);
		$protocol = 'UNKNOWN';
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

	$headers = io_get_request_headers();

	if ($headers === false) {
		trigger_error('Can\'t find request headers', \E_USER_WARNING);
		$headers = [];
	}

	$body = file_get_contents('php://input');

	return [
		0 => $protocol,
		'protocol' => $protocol,
		1 => $method,
		'method' => $method,
		2 => $url,
		'url' => $url,
		3 => $headers,
		'headers' => $headers,
		4 => $body,
		'body' => $body,
	];
}

/**
 * Получить заголовки текущего запроса
 * Имена заголовков приводятся к виду Имя-Заголовка. Будте внимательны,
 * при использовании CamelCase или аббривиатур, буквы в верхнем регистре
 * в середине слова будут приведены к нижнему.
 * @return array
 */
function io_get_request_headers() {
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

/**
 * Отправить ответ
 * @param int $code
 * @param array $headers массив или ассоциативный массив заголовков
 * @param string $body
 * @return boolean
 */
function io_send_response($code, $headers, $body) {
	if (\headers_sent()) {
		\trigger_error('Headers already sent', \E_USER_WARNING);
		echo $body;

		return false;
	} else {
		\http_response_code($code);

		foreach ($headers as $name => $value) {
			if (is_string($name)) {
				$header = $name.': '.$value;
			} else {
				$header = $value;
			}

			\header($header, true);
		}

		echo $body;

		return true;
	}
}

/**
 * Получить опции командной строки
 * @param array $args аргументы командной строки. Если не переданы, используется $argv.
 * @global array $argv
 * @return array
 */
function io_opt($args = null) {
	if ($args === null) {
		global $argv;
		$args = $argv;
		array_shift($args);
	}

	$opt = [];
	foreach ($args as $v) {
		$kv = explode('=', $v);
		if (count($kv) === 2) {
			list($name, $value) = $kv;
			$value = trim($value);
		} else {
			$name = $v;
			$value = true;
		}
		
		$name = ltrim(trim($name), '-');
		$opt[$name] = $value;
	}
	
	return $opt;
}