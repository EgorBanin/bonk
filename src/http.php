<?php

namespace wub;

/**
 * Выполнить HTTP-запрос
 * Для работы функции необходима включённая директива allow_url_fopen.
 * @param string $method
 * @param string $url
 * @param array $headers массив строк-заголовков
 * @param string $body
 * @param array $options опции контекста
 * @throws \Exception
 */
function http_request($method, $url, array $headers, $body, array $options = []) {
	$options['http'] = [
		'method' => $method,
		'header' => $headers,
		'content' => $body,
		'max_redirects' => 0,
		'ignore_errors' => 1,
	];
	$context = stream_context_create($options);
	$stream = @fopen($url, 'r', false, $context);
	if ($stream === false) {
		throw new \Exception('Не удалось выполнить запрос '.$method.' '.$url);
	}
	$meta = stream_get_meta_data($stream);
	$responseHeaders = isset($meta['wrapper_data'])? $meta['wrapper_data'] : [];
	$responseBody = stream_get_contents($stream);
	fclose($stream);
	$responseStatus = [];
	if (preg_match(
		'/^(?<protocol>https?\/[0-9\.]+)\s+(?<code>\d+)\s+(?<comment>\S.*)$/i',
		reset($responseHeaders),
		$responseStatus
	) !== 1) {
		throw new \Exception('Не удалось распарсить статус ответа');
	}
	
	return [
		'code' => $responseStatus['code'],
		'headers' => $responseHeaders,
		'body' => $responseBody,
	];
}

