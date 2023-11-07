<?php declare(strict_types=1);

namespace frm;

return function(HttpRequest $rq, HttpResponse $rs) { // you can return simple closure
	return $rs->setBody(ob_include(__DIR__ . '/echo.phtml', [
		'locator' => $rq->getLocator(),
		'headers' => $rq->getHeaders(),
		'body' => $rq->getBody(),
	]));
};