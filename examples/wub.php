<?php



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
	$val = arr_get($array, $key, $defaultValue);
	unset($array[$key]);
	
	return $val;
}

/**
 * Пользовательский поиск по массиву
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
 * Получить массив только с указанными ключами
 * @param array $array
 * @param array $keys
 * @return array
 */
function arr_pick($array, $keys) {
	return array_intersect_key($array, array_flip($keys));
}

/**
 * Получить массив без указанных ключей
 * @param array $array
 * @param array $keys
 * @return array
 */
function arr_omit($array, $keys) {
	return array_diff_key($array, array_flip($keys));
}

/**
 * Проиндексировать список массивов по ключу
 * @param array $array массив ассосиативных массивов
 * @param string $key
 * @return array
 */
function arr_index($array, $key) {
	$index = [];
	foreach ($array as $v) {
		if (array_key_exists($key, $v)) {
			$index[$v[$key]] = $v;
		}
	}
	
	return $index;
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
 * Отправить ответ редиректа и завершить выполнение
 * @param string $url
 * @param int $code
 */
function io_redirect($url, $code = 302) {
	io_send_response($code, ['Location: '.$url], '');
	exit(0);
}

/**
 * Получить переменную из $_GET массива
 * @param string $key
 * @param mixed $defaultValue
 * @return mixed
 */
function io_get($key, $defaultValue = null) {
	return array_key_exists($key, $_GET)? $_GET[$key] : $defaultValue;
}

/**
 * Получить переменную из $_POST массива
 * @param string $key
 * @param mixed $defaultValue
 * @return mixed
 */
function io_post($key, $defaultValue = null) {
	return array_key_exists($key, $_POST)? $_POST[$key] : $defaultValue;
}

/**
 * Получить переменную из $_SESSION массива
 * @param string $key
 * @param mixed $defaultValue
 * @return mixed
 */
function io_session($key, $defaultValue = null) {
	return array_key_exists($key, $_SESSION)? $_SESSION[$key] : $defaultValue;
}

/**
 * Получить опции командной строки
 * @global array $argv
 * @return array
 */
function io_opt() {
	global $argv;
	$args = $argv;
	array_shift($args);
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
 * Включение очень простой автозагрузки классов
 * Если переданы базовые директории, они будут добавлены в include path
 * @param string ...$baseDir
 */
function enable_class_autoloading() {
	$baseDirs = func_get_args();
	if ($baseDirs) {
		$include = '';
		foreach ($baseDirs as $baseDir) {
			$include .= PATH_SEPARATOR.$baseDir;
		}
		set_include_path(get_include_path().$include);
	}

	spl_autoload_register(function($className) {
		$fileName = stream_resolve_include_path(
			strtr(ltrim($className, '\\'), '\\', '/').'.php'
		);

		if ($fileName) {
			require_once $fileName;
		}
	});
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

/**
 * var_dump с выводом файла и строки, в котором он вызван
 * Функция помечена как deprecated для дополнительной подсветки IDE
 * @param mixed ...$var
 * @deprecated
 */
function DEBUG() {
	$backtrace = debug_backtrace(
		DEBUG_BACKTRACE_IGNORE_ARGS & ~DEBUG_BACKTRACE_PROVIDE_OBJECT,
		1
	);
	echo $backtrace[0]['file'].':'.$backtrace[0]['line']."\n";
	$vars = func_get_args();
	call_user_func_array('var_dump', $vars);
}

/**
 * Выполнить запрос с помощью CURL
 * @param string $method
 * @param string $url
 * @param array $headers
 * @param string $body
 * @throws \Exception
 */
function http_curl_request($method, $url, array $headers, $body) {
	$curl = curl_init($url);
	curl_setopt_array($curl, [
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_HEADER => true,
		CURLOPT_CUSTOMREQUEST => $method,
		CURLOPT_HTTPHEADER => $headers,
		CURLOPT_POSTFIELDS  => $body,
		CURLOPT_SSL_VERIFYHOST => false,
		CURLOPT_SSL_VERIFYPEER => false,
	]);
	$result = curl_exec($curl);

	if ($result === false) {
		$error = curl_error($curl);
		curl_close($curl);
		throw new \Exception($error);
	}

	$headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
	$responseHeaders = substr($result, 0, $headerSize);
	$headers = array_filter(explode("\r\n", $responseHeaders));
	$responseBody = substr($result, $headerSize);
	$response = [
		'code' => curl_getinfo($curl, CURLINFO_HTTP_CODE),
		'headers' => $headers,
		'body' => $responseBody,
	];
	curl_close($curl);

	return $response;
}
