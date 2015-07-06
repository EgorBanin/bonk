<?php

$appPath = realpath('../src/app');

// настраиваем include_path
$include_path = get_include_path();
set_include_path($include_path.PATH_SEPARATOR.$appPath);

require 'autoload.php';
