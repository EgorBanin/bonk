<?php

namespace wub;

function file_empty_dir($dir, $filter = null) {
	if ( ! $filter) {
		$filter = function($file) {
			return ($file !== '.' && $file !== '..');
		};
	}

	$files = scandir($dir);
	$result = true;
	foreach ($files as $name) {
		if ( ! $filter($name)) {
			continue;
		}

		$file = $dir.'/'.$name;
		if (is_dir($file)) {
			$result =
				$result
				&& file_empty_dir($file, $filter) // ! рекурсия
				&& @rmdir($file);
		} else {
			$result = $result && @unlink($file);
		}
	}

	return $result;
}