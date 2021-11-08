<?php declare(strict_types=1);

namespace frm;

// App -- неинтерактивное приложение, обработчик запросов
class App {
	public function __construct(
		private Router $router,
		private Registry $registry,
	) {}

	// run -- обработка запроса и вывод ответа
	public function run(Request $rq, Response $rs, $out, $err) {
		$route = $this->router->route($rq->getLocator());
		if ($route === null) {
			return $rs->notFound('Not found')->send($err);
		}

		try {
			$handler = $this->registry->get($route->handlerId);
		} catch(\Exception $e) { // todo
			throw $e;
		}

		$rq->addAppParams($route->params);

		try {
			$rs = $handler($rq, $rs);
		} catch(\Throwable $e) { // todo
			return $rs->internalError($e->getMessage())->send($err);
		}

		return $rs->send($out);
	}

}