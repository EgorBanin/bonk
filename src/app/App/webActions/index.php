<?php

return function() {
	
	return $this->tpl('layout.php', [
		'content' => $this->tpl('index.php')
	]);
	
};
