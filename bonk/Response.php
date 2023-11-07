<?php declare(strict_types=1);

namespace frm;

interface Response
{
	/**
	 * Отпавить ответ в поток
	 * @param $file resource ресурс потока
	 * @throws Exception
	 */
	public function send($file);

	public function setOutput(string $output): self;

	public static function notFound(string $message, $context = []): self;

	public static function internalError(string $message, $context = []): self;

}