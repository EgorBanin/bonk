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

