<?php declare(strict_types=1);

namespace wub;

/**
 * Приложение
 * Неинтерактивное приложение, обработчик запроса.
 */
class App {

	private IRouter $router;

	private IRegistry $registry;

	public function __construct(
		IRouter $router,
		IRegistry $registry
	) {
		$this->router = $router;
		$this->registry = $registry;
	}

	/**
	 * Запуск приложения
	 * Обработка запроса и вывод ответа.
	 * @param IRequest $rq
	 * @param IResponse $rs
	 * @param false|resource $out
	 * @param false|resource $err
	 * @return int
	 * @throws \Exception
	 */
	public function run(IRequest $rq, IResponse $rs, $out = \STDOUT, $err = \STDERR) {
		$route = $this->router->route($rq->getLocator());
		if ($route === null) {
			return $rs->notFound('Не найдено')->send($err);
		}

		try {
			$handler = $this->registry->get($route->getHandlerId());
		} catch(\Exception $e) { // todo
			throw $e;
			return $rs->notFound('Не найдено')->send($err);
		}

		$rq->addAppParams($route->getParams());

		try {
			$rs = $handler($rq, $rs);
		} catch(\Throwable $e) { // todo
			return $rs->internalError($e->getMessage())->send($err);
		}

		return $rs->send($out);
	}

}