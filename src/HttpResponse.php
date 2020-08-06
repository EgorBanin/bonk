<?php declare(strict_types=1);

namespace wub;

class HttpResponse implements IResponse {

	private int $code;

	private array $headers;

	private string $body;

	public function __construct(int $code, array $headers, string $body) {
		$this->code = $code;
		$this->headers = $headers;
		$this->body = $body;
	}

	public function send($file): int {
		if (\headers_sent()) {
			throw new \Exception('Ошибка при отправке ответа: заголовки уже отправлены');
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
		
		\fwrite($file, $this->body);

		return 0;
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