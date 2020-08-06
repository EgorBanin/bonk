<?php declare(strict_types=1);

namespace wub;

/**
 * Ответ
 */
interface IResponse {

	public function send($file): int;

	public static function notFound(string $message, $context = []): self;

	public static function internalError(string $message, $context = []): self;

}