<?php declare(strict_types=1);

namespace bonk;

require __DIR__ . '/../../../vendor/autoload.php';

$router = new Router([
	'~^GET /$~' => 'web/index.php',
    '~^GET /hello$~' => 'web/helloWorld.php',
]);
$registry = new Registry([
	'web/' => __DIR__ . '/web',
	'' => __DIR__ . '/factories',
]);
$app = new App($router, $registry);
$rq = HttpRequest::fromGlobals($_SERVER, $_GET, $_POST, $_FILES, $_COOKIE, 'php://input');
$rs = HttpResponse::ok();
$exitCode = $app->run($rq, $rs, fopen('php://output', 'w'), fopen('php://stderr', 'w'));
exit($exitCode);