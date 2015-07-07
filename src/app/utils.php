<?php

namespace utils;

function obInclude($file, array $params = []) {
	extract($params);
	ob_start();
	require func_get_arg(0);

	return ob_get_clean();	
}

/**
 * Разбор строки по шаблону
 * 
 * @param string $str
 * @param string $pattern
 * @return array|false
 */
function strParse($str, $pattern, $limiter = '%') {
	$map = [];
	$safeLimiter = preg_quote($limiter, '~');
	
	if ($safeLimiter !== $limiter) {
		$safeLimiter = '\\\\'.$safeLimiter;
	}
	
	$regex = preg_replace_callback(
		"~$safeLimiter(.+?)$safeLimiter~i",
		function($matches) use(&$map) {
			$name = 'n'.count($map);
			$map[$name] = stripcslashes($matches[1]);
			$regex = '.+';
			return "(?<$name>$regex)";
		},
		preg_quote($pattern, '~')
	);
	
	$matches = [];
	if (preg_match("~$regex~i", $str, $matches)) {
		$result = [];
		foreach ($matches as $key => $val) {
			if (array_key_exists($key, $map)) {
				$result[$map[$key]] = $val;
			}
		}
		
		return $result;
	} else {
		return false;
	}
}

