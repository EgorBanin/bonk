<?php

return function() {
	
	$posts = $this->db->posts->find(['pubTs' => ['$lte' => time()]]);
	
	return $this->tpl('layout.php', [
		'content' => $this->tpl('posts/index.php', ['posts' => $posts])
	]);
	
};
