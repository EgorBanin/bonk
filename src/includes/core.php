<?php

namespace core;

/**
 * Включить скрипт, буферизируя вывод
 * @param string $file
 * @param array $params экспортируемые переменные
 * @return string
 */
function ob_include($file, array $params) {
	extract($params);
	ob_start();
	require $file;
	
	return ob_get_clean();
}

function tpl($tpl, array $params = []) {
	return ob_include($tpl, $params);
}

function run($action) {
	return ob_include($action, []);
}
