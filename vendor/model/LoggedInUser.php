<?php

class LoggedInUser {

	private $userName;

	public function __construct($userName) {
		assert(!is_null($userName));
		$this->userName = $userName;
	}

	public function getUserName() {
		return $this->userName;
	}

}