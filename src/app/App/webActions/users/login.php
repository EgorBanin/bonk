<?php

return function() {
	
	if ($this->user->auth()) {
		return \Http\Response::redirect('/');
	}
	
	$db = $this->db;
	/*
	$input = new \Validation\Input($_POST, [
		'login' => [
			'required' => 'Обязательно укажите логин',
			function($val) use($db) {
				$user = $db->users->find(['login' => $val]);
				
				if ( ! $user) {
					return $this->error(printf('Пользователь с логином %s не найден', $val));
				} else {
					$this->data('user', $user);
					
					return $val;
				}
			}
		],
		'password' => [
			'required' => 'Обязательно укажите пароль',
			function($val) {
				return $this->data('user')->password === hash($val);
			}
		]
	]);
	
	if ($input->isValid()) {
		$this->user->login($input->data('user')->id);
	}
	*/
	
};

