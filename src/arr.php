<?php

namespace wub;

function arr_get($array, $key, $defaultValue = null) {
	return array_key_exists($key, $array)? $array[$key] : $defaultValue;
}

function arr_take(&$array, $key, $defaultValue = null) {
	$val = get($array, $key, $defaultValue);
	unset($array[$key]);
	
	return $val;
}

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