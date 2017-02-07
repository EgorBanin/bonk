<?php

namespace wub;

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