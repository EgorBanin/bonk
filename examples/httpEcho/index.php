<?php

/**
 * php -S localhost:8000
 * Веб-сервер для отладки функции http_request.
 */

require __DIR__.'/../wub.php';

$request = io_get_request();

echo $request['method'].' '.$request['url'], "\n";
foreach ($request['headers'] as $header => $value) {
	echo $header.': '.$value, "\n";
}
echo "\n", $request['body'];