<?php

namespace wub;

/**
 * Включение очень простой автозагрузки классов
 * Если переданы базовые директории, они будут добавлены в include path
 * @param string ...$baseDir
 */
function enable_class_autoloading() {
	$baseDirs = func_get_args();
	if ($baseDirs) {
		$include = '';
		foreach ($baseDirs as $baseDir) {
			$include .= PATH_SEPARATOR.$baseDir;
		}
		set_include_path(get_include_path().$include);
	}

	spl_autoload_register(function($className) {
		$fileName = stream_resolve_include_path(
			strtr(ltrim($className, '\\'), '\\', '/').'.php'
		);

		if ($fileName) {
			require_once $fileName;
		}
	});
}

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
 * var_dump с выводом файла и строки, в котором он вызван
 * Функция помечена как deprecated для дополнительной подсветки IDE
 * @param mixed ...$var
 * @deprecated
 */
function DEBUG() {
	$backtrace = debug_backtrace(
		DEBUG_BACKTRACE_IGNORE_ARGS & ~DEBUG_BACKTRACE_PROVIDE_OBJECT,
		1
	);
	echo $backtrace[0]['file'].':'.$backtrace[0]['line']."\n";
	$vars = func_get_args();
	call_user_func_array('var_dump', $vars);
}