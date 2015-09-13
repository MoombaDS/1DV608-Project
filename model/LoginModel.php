<?php

class LoginModel {
	private static $loggedIn = 'LoginModel::LoggedIn';

	private $user;

	public function __construct() {
		$this->user = new User('Admin', 'Password');
		session_start();
	}
	
	public function validateCredentialsAndLogIn(User $user) {
		assert(!is_null($user));
		$result = ($user->getUsername() == $this->user->getUsername() && $user->getPassword() == $this->user->getPassword());
		if ($result) {
			$_SESSION[self::$loggedIn] = true;
		}
		return $result;
	}

	public function isLoggedIn() {
		if (isset($_SESSION[self::$loggedIn])) {
			return $_SESSION[self::$loggedIn];
		}
		return false;
	}

	public function logOut() {
		if (isset($_SESSION[self::$loggedIn]) && $_SESSION[self::$loggedIn]) {
			$_SESSION[self::$loggedIn] = false;
			return true;
		}
		return false;
	}

}