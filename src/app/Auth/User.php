<?php

namespace Auth;

class User {
	
	private $fingerprintFunc;

	public function __construct($sessionDir = null, $fingerprintFunc = null) {
		if ($sessionDir) {
			session_save_path($sessionDir);
		}

		$this->fingerprintFunc = $fingerprintFunc?: [$this, 'fingerprint'];
	}

	public function login($id, $remember = 0) {
		session_set_cookie_params($remember);
		//session.gc_maxlifetime

		$this->startSession();
		$_SESSION['id'] = $id;
		$_SESSION['fingerprint'] = call_user_func($this->fingerprintFunc);
		$sessionKey = session_id();
		session_write_close();

		return $sessionKey;
	}

	public function logout() {
		$this->startSession();
		session_destroy();
	}

	/**
	 * Авторизация по сессии
	 * @return mixed|false идентификатор или false в случае неудачи
	 */
	public function auth() {
		if (isset($_REQUEST[session_name()]) || isset($_COOKIE[session_name()])) {
			$this->startSession();
		}

		if (isset($_SESSION['id']) && $_SESSION['fingerprint'] === call_user_func($this->fingerprintFunc)) {
			return $_SESSION['id'];
		} else {
			return false;
		}
	}

	/**
	 * @return string
	 */
	public function fingerprint() {
		$fingerprint = [
			isset($_SERVER['REMOTE_ADDR'])? $_SERVER['REMOTE_ADDR'] : null,
			isset($_SERVER['HTTP_X_FORWARDED_FOR'])? $_SERVER['HTTP_X_FORWARDED_FOR'] : null,
			isset($_SERVER['HTTP_USER_AGENT'])? $_SERVER['HTTP_USER_AGENT'] : null,
		];

		return md5(json_encode($fingerprint));
	}

	/**
	 * Безопасный старт сессии
	 */
	private function startSession() {
		if (session_status() !== PHP_SESSION_ACTIVE) {
			session_start();
		}
	}
	
}

