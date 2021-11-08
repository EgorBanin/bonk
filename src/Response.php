<?php declare(strict_types=1);

namespace frm;

interface Response {
	public function send($file): int;

	public static function notFound(string $message, $context = []): self;

	public static function internalError(string $message, $context = []): self;
}