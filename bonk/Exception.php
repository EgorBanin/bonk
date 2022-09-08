<?php declare(strict_types=1);

namespace bonk;

class Exception extends \Exception {
	public const CODE_SYSTEM = 1;

    public static function system(string $message, ?\Throwable $previous = null): self {
        return new Exception($message, self::CODE_SYSTEM, $previous);
    }
}