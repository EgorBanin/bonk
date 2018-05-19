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
	$options['http'] = ($options['http']?? []) + [
		'method' => $method,
		'header' => $headers,
		'content' => $body,
		'max_redirects' => 0,
		'ignore_errors' => 1,
	];
	$context = stream_context_create($options);
	set_error_handler(function($code, $message, $file, $line) use($method, $url) {
		restore_error_handler();
		throw new \Exception(
			'Не удалось выполнить запрос ' . $method . ' ' . $url,
			1,
			new \ErrorException($message, $code, $code, $file, $line)
		);
	});
	$stream = fopen($url, 'r', false, $context);
	$meta = stream_get_meta_data($stream);
	$responseHeaders = isset($meta['wrapper_data'])? $meta['wrapper_data'] : [];
	$responseBody = stream_get_contents($stream);
	fclose($stream);
	$responseStatus = [];
	if (preg_match(
		'/^(?<protocol>https?\/[0-9\.]+)\s+(?<code>\d+)(?:\s+(?<comment>\S.*))?$/i',
		trim(reset($responseHeaders)),
		$responseStatus
	) !== 1) {
		throw new \Exception('Не удалось распарсить статус ответа');
	}
	
	return [
		0 => $responseStatus['code'],
		'code' => $responseStatus['code'],
		1 => $responseHeaders,
		'headers' => $responseHeaders,
		2 => $responseBody,
		'body' => $responseBody,
	];
}

class MultipartFormData {

	private $params = [];

	public function addParam(array $headers, $value) {
		$this->params[] = [
			'headers' => $headers,
			'value' => $value,
		];

		return true;
	}

	public function addTextParam($name, $value, $charset = null) {
		$headers = ['Content-Disposition: form-data; name="' . $name . '"'];

		if ($charset) {
			$headers[] = 'Content-Type: text/plain; charset=' . $charset;
		}

		return $this->addParam($headers, $value);
	}

	public function addFileParam($name, $value, $filename = null, $type = null) {
		$disposition =  'Content-Disposition: form-data; name="' . $name . '"';
		if ($filename) {
			$disposition .= '; filename="' . $filename . '"';
		}
		$headers = [$disposition];

		if ($type) {
			$headers[] = 'Content-Type: ' . $type;
		}

		return $this->addParam($headers, $value);
	}

	public function loadFile($name, $filename, $type = null) {
		if ( ! is_readable($filename)) {
			throw new \Exception('Не удалось прочитать файл ' . $filename);
		}
		$value = file_get_contents($filename);

		if ( ! $type) {
			$finfo = new finfo(FILEINFO_MIME);
			$type = $finfo->file($filename);
		}

		return $this->addFileParam($name, $value, basename($filename), $type);
	}

	public function encode() {
		$boundary = $this->generateBoundary(10);
		if ($boundary === null) {
			throw new \Exception('Не удалось сгенерировать boundary');
		}

		$eol = "\r\n";
		$body = '';
		foreach ($this->params as $param) {
			$body .= '--' . $boundary . $eol;
			$body .= implode($eol, $param['headers']) . $eol;
			$body .= $eol;
			$body .= $param['value'] . $eol;
		}
		$body .= '--' . $boundary . '--' . $eol;
		$body .= $eol;

		return [$boundary, $body];
	}

	private function generateBoundary($tryLimit) {
		do {
			--$tryLimit;
			$boundary = bin2hex(random_bytes(10));
			foreach ($this->params as &$param) {
				if (strpos($param['value'], $boundary) !== false) {
					$boundary = null;
					break;
				}
			}
		} while ($boundary === null && $tryLimit > 0);

		return $boundary;
	}

}
