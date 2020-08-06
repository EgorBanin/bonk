<?php declare(strict_types=1);

namespace wub;

interface IRequest {

	/**
	 * Получить соответсвующий запросу локатор
	 * Локатор нужен для роутинга. Это может быть, например, HTTP-метод и путь: GET /collection/123.
	 */
	public function getLocator(): string;

	public function addAppParams(array $params);

}