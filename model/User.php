<?php

class User {
	private $username;
	private $password;

	public function __construct($username, $password) {
		assert(is_string($username));
		assert(is_string($password));
		$this->username = $username;
		$this->password = $password;
	}

	public function getUsername() {
		return $this->username;
	}

	public function getPassword() {
		return $this->password;
	}

}