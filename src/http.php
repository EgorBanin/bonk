<?php

namespace wub;

/**
 * Выполнить запрос с помощью CURL
 * @param string $method
 * @param string $url
 * @param array $headers
 * @param string $body
 * @throws \Exception
 */
function http_curl_request($method, $url, array $headers, $body) {
	$curl = curl_init($url);
	curl_setopt_array($curl, [
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_HEADER => true,
		CURLOPT_CUSTOMREQUEST => $method,
		CURLOPT_HTTPHEADER => $headers,
		CURLOPT_POSTFIELDS  => $body,
		CURLOPT_SSL_VERIFYHOST => false,
		CURLOPT_SSL_VERIFYPEER => false,
	]);
	$result = curl_exec($curl);

	if ($result === false) {
		$error = curl_error($curl);
		curl_close($curl);
		throw new \Exception($error);
	}

	$headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
	$responseHeaders = substr($result, 0, $headerSize);
	$headers = array_filter(explode("\r\n", $responseHeaders));
	$responseBody = substr($result, $headerSize);
	$response = [
		'code' => curl_getinfo($curl, CURLINFO_HTTP_CODE),
		'headers' => $headers,
		'body' => $responseBody,
	];
	curl_close($curl);

	return $response;
}
