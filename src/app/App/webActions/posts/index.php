<?php

return function() {
	
	$posts = $this->db->posts->find(['$gte' => ['pubTs' => time()]]);
	
	return $this->tpl('layout.php', [
		'content' => $this->tpl('posts/index.php', ['posts' => $posts])
	]);
	
};
