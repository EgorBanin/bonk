<?php declare(strict_types=1);

namespace frm;

require __DIR__ . '/../../../vendor/autoload.php';

$router = new Router([
    '~^GET /helloWorld$~' => 'web/helloWorld.php',
	'~^.+ /echo$~' => 'web/echo.php',
	'~^GET /$~' => 'web/index.php',
]);
$registry = new Registry([
	'web/' => __DIR__ . '/web',
	'' => __DIR__ . '/services',
]);
$app = new App($router, $registry);
$rq = HttpRequest::fromGlobals($_SERVER, $_GET, $_POST, $_FILES, $_COOKIE, 'php://input');
$rs = HttpResponse::ok();
$exitCode = $app->run($rq, $rs, fopen('php://output', 'w'), fopen('php://stderr', 'w'));
exit($exitCode);