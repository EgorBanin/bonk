<?php

use http\send_response;
use http\response;
require_once 'core/module.php';
require_once 'core/http/module.php';

$server_env = $_SERVER?: [];
$input_stream = fopen('php://input', 'r');
$request = \http\request_from_env($server_env, $input_stream);
fclose($input_stream);

$output_stream = fopen('php://output', 'w');
\http\send_response(new \http\response([], 'Hello world'), $output_stream);
fclose($output_stream);