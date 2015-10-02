<?php

class UserDAL {
	private static $fileName = '_usercookie.data';

	/**
	 * Load a User object from the file which contains the username and hashed token.
	 *
	 * @return a User object containing the username and hashed token, or null if such does not exist
	 */
	public function getUserWithToken(User $user) {
		try {
			$fileContent = @file_get_contents($user->getUsername() . self::$fileName);
		} catch (Exception $e) {
			// No file found
			return null;
		}
		if ($fileContent !== FALSE) {
			return unserialize($fileContent);
		}
		return null;
	}

	/**
	 * Save a user and hashed token into the file. Since there's only one user we won't bother with the possibility
	 * of saving multiple users with tokens.
	 *
	 * @return null
	 */
	public function saveUserWithToken(User $user) {
		$content = serialize($user);

		file_put_contents($user->getUsername() . self::$fileName, $content);
	}
}