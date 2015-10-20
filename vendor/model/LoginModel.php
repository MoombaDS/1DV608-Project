<?php

class LoginModel {
	private static $loggedIn = 'LoginModel::LoggedIn';

	private $user;
	private $userDAL;

	public function __construct(UserDAL $dal) {
		// Set up the hardcoded admin user for simplicity
		$this->user = new User('Admin', 'Password');
		$this->userDAL = $dal;
		// Start the session
		session_start();
	}

	/**
	 * Check if the provided user credentials are correct and set the session logged in variable to be true if they are
	 * 
	 * @param $user, the user to validate against stored credentials
	 * @return true if credentials are valid, false if not
	 */
	
	public function validateCredentialsAndLogIn(User $user) {
		assert(!is_null($user));
		$result = false;
		if ($user->getUsername() == 'Admin') {
			$result = ($user->getUsername() == $this->user->getUsername() && $user->getPassword() == $this->user->getPassword());
		} else {
			$savedUser = $this->userDAL->getRegisteredUser($user);
			if (is_null($savedUser)) {
				$result = false; // User did not exist
			} else {
				$result = ($user->getPassword() == $this->verifyHashedToken($user->getPassword(), $savedUser->getPassword()));
			}
		}
		if ($result) {
			$_SESSION[self::$loggedIn] = true;
		}
		return $result;
	}

	/**
	 * Check if the provided user credentials from the cookies are correct and set the session logged in variable to be true if they are
	 * 
	 * @param $user, the user created from the cookie to validate against stored credentials
	 * @return true if the credentials are valid, false if not
	 */

	public function validateCookieAndLogIn(User $user) {
		assert (!is_null($user));
		$storedUser = $this->userDAL->getUserWithToken($user);
		
		// If it's null there is no data and the cookie is probably tampered with
		if (is_null($storedUser)) {
			return false;
		}

		$result = ($user->getUsername() == $storedUser->getUsername() && $this->verifyHashedToken($user->getPassword(), $storedUser->getPassword()));
		if ($result) {
			$_SESSION[self::$loggedIn] = true;
		}
		return $result;
	}

	private function verifyHashedToken($token, $hashedToken) {
		return crypt($token, $hashedToken) == $hashedToken;
	}

	/**
	 * Check the session information to decide if it's logged in
	 * 
	 * @return true if logged in, false if not
	 */

	public function isLoggedIn() {
		if (isset($_SESSION[self::$loggedIn])) {
			return $_SESSION[self::$loggedIn];
		}
		return false;
	}

	/**
	 * Log out and set the session logged in variable to be false
	 * 
	 * @return true if successful logout, false if user was not logged in
	 */

	public function logOut() {
		if (isset($_SESSION[self::$loggedIn]) && $_SESSION[self::$loggedIn]) {
			$_SESSION[self::$loggedIn] = false;
			return true;
		}
		return false;
	}

	/**
	 * Generate a login token for storing in cookies and also save a hashed version of this token
	 * along with the username in a file for later reference.
	 *
	 * @return the randomly generated token for saving in cookies
	 */

	public function generateAndSaveToken($userName) {
		$token = md5(uniqid(rand(), true));
		$hashedToken = $this->generateHash($token);
		$user = new User($userName, $hashedToken);
		$this->userDAL->saveUserWithToken($user);
		return $token;
	}

	private function generateHash($token) {
    	if (defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH) {
    	    $salt = '$2y$11$' . substr(md5(uniqid(rand(), true)), 0, 22);
    	    return crypt($token, $salt);
    	}
	}

}