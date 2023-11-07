<?php declare(strict_types=1);

namespace frm;

class HttpResponse implements Response {

	public function __construct(
		private int $code,
		private array $headers,
		private string $body,
	) {}

	/**
	 * @inheritDoc
	 */
	public function send($file) {
		if (\headers_sent()) {
			throw Exception::system('headers already sent');
		}

		\http_response_code($this->code);
		foreach ($this->headers as $name => $value) {
			if (is_string($name)) {
				$header = $name . ': ' . $value;
			} else {
				$header = $value;
			}
			\header($header, true);
		}
		
		$r = \fwrite($file, $this->body);
		if ($r === false) {
			throw Exception::system("can't send response");
		}

		return 0;
	}

	public function setOutput(string $output): self
	{
		return $this->setBody($output);
	}

	public static function notFound(string $message, $context = []): self {
		return new self(404, [], $message);
	}

	public static function internalError(string $message, $context = []): self {
		return new self(500, [], $message);
	}

	public static function ok(): self {
		return new self(200, [], '');
	}

	public function setBody(string $body): self {
		$this->body = $body;

		return $this;
	}

}