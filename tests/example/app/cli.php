<?php declare(strict_types=1);

namespace bonk;

require __DIR__ . '/../../../vendor/autoload.php';

$router = new Router([
	'~initDb~' => 'cli/initDb.php', // todo {command}
]);
$registry = new Registry([
	'cli/' => __DIR__ . '/cli',
	'' => __DIR__ . '/factories',
]);
$app = new App($router, $registry);
\array_shift($argv);
$input = \stream_isatty(\STDIN)? '' : \stream_get_contents(\STDIN);
$rq = new CliRequest($argv, $input);
$rs = new CliResponse(0, '');
$exitCode = $app->run($rq, $rs, fopen('php://output', 'w'), fopen('php://stderr', 'w'));
exit($exitCode);