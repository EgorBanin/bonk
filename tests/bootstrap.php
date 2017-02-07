<?php

$basePath = realpath(__DIR__.'/../src');

// настраиваем include_path
$include_path = get_include_path();
set_include_path($include_path.PATH_SEPARATOR.$basePath);
