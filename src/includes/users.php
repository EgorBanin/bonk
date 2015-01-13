<?php

namespace users;

function find_one(\MongoDB $db, $id) {
	$result = $db->users->findOne(['id' => $id]);
	
	return $result? user($result) : null;
}
