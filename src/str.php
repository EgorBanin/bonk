<?php

namespace wub;

function str_template($template, $vars) {
	$replaces = [];
	foreach ($vars as $name => $value) {
		$replaces["\{$name\}"] = $value;
	}

	return strtr($template, $replaces);
}