<?php

class RegisterModel {
	private $userDAL;

	public function __construct(UserDAL $dal) {
		$this->userDAL = $dal;
	}

	/**
	 * Attempt to register a user in the system
	 * 
	 * @param $user, the user to register
	 * @return true if registration was successful, false if not (i.e. the user already exists in the system)
	 */

	public function registerUser(User $user) {
		if ($user->getUsername() == 'Admin') {
			return false; // Admin is always taken
		} else {
			try {
				// Recreate the user but with an encrypted password
				$hashedUser = new User($user->getUsername(), $this->generateHash($user->getPassword()));
				$this->userDAL->saveUserRegistration($hashedUser);
				return true;
			} catch (Exception $e) {
				return false; // Return false if the username existed
			}
		}
		return false;
	}

	/**
	 * Generate the hash for saving the user's password in a more secure way
	 * 
	 * @param $password the password to hash
	 * @return the hashed password
	 */

	private function generateHash($password) {
    	if (defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH) {
    	    $salt = '$2y$11$' . substr(md5(uniqid(rand(), true)), 0, 22);
    	    return crypt($password, $salt);
    	}
	}
}