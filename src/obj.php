<?php

namespace wub;

/**
 * Установить свойства объекта
 * @param object $obj
 * @param array $properties
 */
function obj_init($obj, $properties) {
	$func = function($properties) {
		foreach (get_object_vars($this) as $name => $val) {
			if (array_key_exists($name, $properties)) {
				$this->{$name} = $properties[$name];
			}
		}
	};
	$closure = $func->bindTo($obj, $obj);
	$closure($properties);
}

/**
 * Преобразовать объект в массив
 * Работает аналогично get_object_vars,
 * но получает доступ к защищённым и приватным свойствам.
 * @param type $obj
 * @return type
 */
function obj_to_array($obj) {
	$arr = [];
	$func = function(&$properties) {
		$properties = get_object_vars($this);
	};
	$closure = $func->bindTo($obj, $obj);
	$closure($arr);

	return $arr;
}

