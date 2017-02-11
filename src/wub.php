<?php

namespace wub;

/**
 * Подключение файла с буферизацией вывода
 * @param string $file
 * @param array $params
 * @return string
 */
function ob_include($file, array $params = []) {
	extract($params);
	ob_start();
	require func_get_arg(0);

	return ob_get_clean();	
}

/**
 * var-dump с выводом файла и строки, в котором он вызван
 */
function debug() {
	$backtrace = debug_backtrace(
		DEBUG_BACKTRACE_IGNORE_ARGS & ~DEBUG_BACKTRACE_PROVIDE_OBJECT,
		1
	);
	echo $backtrace[0]['file'].':'.$backtrace[0]['line']."\n";
	$vars = func_get_args();
	call_user_func_array('var_dump', $vars);
}