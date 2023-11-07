<?php declare(strict_types=1);

namespace frm;

class HttpRequest implements Request
{

	private array $appParams = [];

	public function __construct(
		private ?string $protocol,
		private ?string $method,
		private ?string $url,
		private array   $headers,
		private string  $body,
		private array   $queryParams,
		private array   $bodyParams,
		private array   $files,
		private array   $cookies,
		private ?string $ip,
	)
	{
	}

	public static function fromGlobals(
		array $server,
		array $get,
		array $post,
		array $files,
		array $cookie,
			$phpinput
	)
	{
		$method = $server['REQUEST_METHOD'] ?? null;
		$body = file_get_contents($phpinput);
		$bodyParams = $post;
		if ($method !== 'POST' && empty($post)) { // todo: исправить
			parse_str($body, $bodyParams);
		}
		return new self(
			$server['SERVER_PROTOCOL'] ?? null,
			$method,
			$server['REQUEST_URI'] ?? null,
			self::makeHeaders($server),
			$body,
			$get,
			$bodyParams,
			$files,
			$cookie,
			$server['REMOTE_ADDR'] ?? null
		);
	}

	public function getQueryParam(string $name, $default = null)
	{
		return \array_key_exists($name, $this->queryParams) ? $this->queryParams[$name] : $default;
	}

	public function getBodyParam(string $name, $default = null)
	{
		return \array_key_exists($name, $this->bodyParams) ? $this->bodyParams[$name] : $default;
	}

	public function addAppParams(array $params)
	{
		$this->appParams = \array_merge($this->appParams, $params);
	}

	public function getAppParam(string $name, $default = null)
	{
		return \array_key_exists($name, $this->appParams) ? $this->appParams[$name] : $default;
	}

	private static function makeHeaders(array $server): array
	{
		$headers = [];
		foreach ($server as $key => $value) {
			if (\strpos($key, 'HTTP_') === 0) {
				$name = \implode('-', \array_map(function ($v) {
					return \ucfirst(strtolower($v));
				}, \explode('_', substr($key, 5))));
				$headers[$name] = $value;
			}
		}
		return $headers;
	}

	public function getLocator(): string
	{
		return sprintf(
			'%s %s',
			$this->method,
			parse_url($this->url, PHP_URL_PATH)
		);
	}

	public function getHeaders(): array {
		return $this->headers;
	}

	public function getBody(): string {
		return $this->body;
	}

}