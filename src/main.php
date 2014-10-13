<?php

$server_env = $_SERVER?: [];
$input_stream = fopen('php://input', 'r');
$request = \http\request_from_env($server_env, $input_stream);
fclose($input_stream);