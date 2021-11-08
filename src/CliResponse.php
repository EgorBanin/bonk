<?php declare(strict_types=1);

namespace frm;

class CliResponse implements Response {

	public const CODE_OK = 0;
	public const CODE_INTERNAL_ERROR = 1;
	public const CODE_NOT_FOUND = 2;

	private $exitCode;

	private $output;

	public function __construct(int $exitCode, string $output) {
		$this->exitCode = $exitCode;
		$this->output = $output;
	}

	public function setOutput(string $output): self {
		$this->output = $output;

		return $this;
	}

	public function send($file): int { 
		\fwrite($file, $this->output);

		return $this->exitCode;
	}

	public static function notFound(string $message, $context = []): self {
		return new self(self::CODE_NOT_FOUND, $message);
	}

	public static function internalError(string $message, $context = []): self {
		return new self(self::CODE_INTERNAL_ERROR, $message);
	}

}