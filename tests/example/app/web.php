<?php declare(strict_types=1);

namespace frm;

require __DIR__ . '/../../../vendor/autoload.php';

$router = new Router([
	'~GET /~' => 'handlers/index.php',
]);
$registry = new Registry([
	'handlers/' => __DIR__ . '/handlers',
	'' => __DIR__ . '/factories',
]);
$app = new App($router, $registry);
$rq = HttpRequest::fromGlobals($_SERVER, $_GET, $_POST, $_FILES, $_COOKIE, 'php://input');
$rs = HttpResponse::ok();
$exitCode = $app->run($rq, $rs, fopen('php://output', 'w'), fopen('php://stderr', 'w'));
exit($exitCode);