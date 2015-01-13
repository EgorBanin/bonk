<?php

require_once 'includes/users.php';

$id = isset($_GET['id'])? $_GET['id'] : null;

if ($id === null) {
	echo  '400';
	return 1;
}

$user = \users\find_one($GLOBALS['db'], $id);

if ( ! $user) {
	echo '404';
	return 2;
}