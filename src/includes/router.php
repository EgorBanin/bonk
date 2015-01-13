<?php

namespace router;

/**
 * Роутинг
 * @param string $path
 * @param array $routes
 * @return string|null
 */
function route($path, array $routes, array &$matches = []) {
	foreach ($routes as $pattern => $val) {
		if (is_array($val)) {
			$tail = prefix_match($pattern, $path, $matches);
			if ($tail !== null) {
				$recursion_result = route($tail, $val, $matches);
				if ($recursion_result) {
					return $recursion_result;
				}
			}
		} else {
			if (full_match($pattern, $path, $matches)) {
				$replaces = [];
				foreach ($matches as $name => $value) {
					$replaces['{'.$name.'}'] = $value;
				}
				
				return strtr($val, $replaces);
			}
		}
	}
	
	// совпадения не найдены
	return null;
}

/**
 * Проверка совпадения начала строки с шаблоном
 * @param string $pattern
 * @param string $subject
 * @param array &$matches
 * @return string|null
 */
function prefix_match($pattern, $subject, array &$matches) {
	$regex_pattern = get_regex($pattern, true);
	$regex_matches = [];
	if (preg_match($regex_pattern, $subject, $regex_matches)) {
		$matches = array_merge($matches, $regex_matches);
		$tail = preg_replace($regex_pattern, '', $subject, 1);
		
		return $tail;
	} else {
		return null;
	}
}

/**
 * Проверка вовпадения всей строки с шаблоном
 * @param string $pattern
 * @param string $subject
 * @param array &$matches
 * @return boolean
 */
function full_match($pattern, $subject, array &$matches) {
	$regex_pattern = get_regex($pattern);
	$regex_matches = [];
	if (preg_match($regex_pattern, $subject, $regex_matches)) {
		$matches = array_merge($matches, $regex_matches);
		
		return true;
	} else {
		return false;
	}
}

/**
 * Регулярное выражение для поиска совпадений
 * @param string $pattern
 * @param boolean $is_prefix
 * @return string
 */
function get_regex($pattern, $is_prefix = false) {
	$regex = preg_replace(
		'~\\\{([a-z][0-9a-z\_]*)\\\}~i',
		'(?<$1>\w+)',
		preg_quote($pattern, '~')
	);
	
	return '~^'.$regex.($is_prefix? '~' : '\/?$~');
}

function url($action, $params = []) {
	return '#';
}