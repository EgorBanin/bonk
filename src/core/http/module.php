<?php

namespace http;

require_once 'request.php';

/**
 * Получить HTTP-запрос из окружения
 * @param array $server_env
 * @param resource $input_stream
 * @return request
 */
function request_from_env(array $server_env, $input_stream) {
	$uri = isset($server_env['REQUEST_URI'])? $server_env['REQUEST_URI'] : '/';
	$method = isset($server_env['REQUEST_METHOD'])? $server_env['REQUEST_METHOD'] : 'UNKNOWN';
	$body = @stream_get_contents($input_stream)?: '';
	
	return new request($method, $uri, $body);
}