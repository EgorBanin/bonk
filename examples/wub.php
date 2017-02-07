<?php

namespace examples;


/**
 * Получить значение из массива по ключу
 * @param array $array
 * @param mixed $key
 * @param mixed $defaultValue
 * @return mixed
 */
function arr_get($array, $key, $defaultValue = null) {
	return array_key_exists($key, $array)? $array[$key] : $defaultValue;
}

/**
 * Извлечь значение из массива по ключу
 * @param array $array
 * @param mixed $key
 * @param mixed $defaultValue
 * @return mixed
 */
function arr_take(&$array, $key, $defaultValue = null) {
	$val = get($array, $key, $defaultValue);
	unset($array[$key]);
	
	return $val;
}

/**
 * Пользовательский поис по массиву
 * @param array $array
 * @param callback $func
 * @return mixed ключ найденого значения или false, если значение не найдено
 */
function arr_usearch($array, $func) {
	$result = false;
	foreach ($array as $k => $v) {
		if (call_user_func($func, $k, $v)) {
			$result = $k;
			break;
		}
	}
	
	return $result;
}

/**
 * Обновить массив заменяя значения исходного массива
 * соответствующими значениями второго массива
 * @param array $array изменяется по ссылке
 * @param array $update
 */
function arr_update(&$array, $update) {
	array_walk($array, function(&$val, $key, $update) {
		if (array_key_exists($key, $update)) {
			$val = $update[$key];
		}
	}, $update);
}

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
		'method' => $method,
		'url' => $url,
		'headers' => $headers,
		'body' => $body
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
function io_send_request($code, $headers, $body) {
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
 * Отправить ответ редиректа и завершить выполнение
 * @param string $url
 * @param int $code
 */
function io_redirect($url, $code = 302) {
	io_send_request($code, ['Location: '.$url], '');
	exit(0);
}


/**
 * Заменить вхождения строки '{varName}' на соответствующее значение из массива
 * @param string $template
 * @param array $vars
 * @return string
 */
function str_template($template, $vars) {
	$replaces = [];
	foreach ($vars as $name => $value) {
		$replaces['{'.$name.'}'] = $value;
	}

	return strtr($template, $replaces);
}

/**
 * Подключение файла с буферизацией вывода
 * @param string $file
 * @param array $params
 * @return string
 */
function ob_include($file, array $params = []) {
	extract($params);
	ob_start();
	require func_get_arg(0);

	return ob_get_clean();	
}