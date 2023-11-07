<?php declare(strict_types=1);

namespace frm;

/**
 * Неинтерактивное приложение, обработчик запросов
 */
class App {
	public function __construct(
		private Router $router,
		private Registry $registry,
	) {}

	/**
	 * Обработть запроса и вывести ответ
	 *
	 * @param Request $rq
	 * @param Response $rs
	 * @param $out resource
	 * @param $err resource
	 * @return int
	 * @throws Exception
	 */
	public function run(Request $rq, Response $rs, $out, $err): int {
		try {
			[$handlerId, $params] = $this->router->route($rq->getLocator());
		} catch (Exception) {
			return $rs->notFound('Not found')->send($err);
		}

		try {
			$handler = $this->registry->get($handlerId);
		} catch(\Throwable $e) {
			return $rs->internalError($e->getMessage())->send($err);
		}

		$rq->addAppParams($params);

		if (!is_callable($handler)) {
			$handler = fn() => $handler;
		}

		try {
			$rs2 = $handler($rq, $rs);
		} catch(\Throwable $e) {
			return $rs->internalError($e->getMessage())->send($err);
		}

		if (! ($rs2 instanceof Response)) {
			$rs2 = $rs->setOutput($rs2);
		}

		return $rs2->send($out);
	}

}