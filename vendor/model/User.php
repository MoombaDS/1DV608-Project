<?php

/**
*
* A class which represents a User in the system
*
**/

class User {
	private $username;
	private $password;

	public function __construct($username, $password) {
		// Check to make sure the username and password are valid non-empty strings
		assert(is_string($username));
		assert(is_string($password));
		assert(!empty($username));
		assert(!empty($password));
		$this->username = $username;
		$this->password = $password;
	}

	/**
	 * Get the username associated with this user
	 * 
	 * @return the username
	 */

	public function getUsername() {
		return $this->username;
	}

	/**
	 * Get the password associated with this user
	 * 
	 * @return the password
	 */

	public function getPassword() {
		return $this->password;
	}

}