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
 * @param type $obj
 * @param type $inject
 * @return type
 */
function obj_to_array($obj, $inject = true) {
	$arr = [];
	if ($inject) {
		$func = function(&$properties) {
			$properties = get_object_vars($this);
		};
		$closure = $func->bindTo($obj, $obj);
		$closure($arr);
	} else {
		$arr = get_object_vars($obj);
	}

	return $arr;
}

