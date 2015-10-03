<?php

class RegisterModel {
	private $userDAL;

	public function __construct(UserDAL $dal) {
		$this->userDAL = $dal;
	}

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

	private function generateHash($password) {
    	if (defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH) {
    	    $salt = '$2y$11$' . substr(md5(uniqid(rand(), true)), 0, 22);
    	    return crypt($password, $salt);
    	}
	}
}