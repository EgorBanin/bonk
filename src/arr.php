<?php

namespace wub;

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