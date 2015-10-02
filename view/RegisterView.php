<?php

class RegisterView implements View {
	private static $regMessage = 'RegisterView::Message';
	private static $regUserName = 'RegisterView::UserName';
	private static $regPassword = 'RegisterView::Password';
	private static $regPasswordRep = 'RegisterView::PasswordRepeat';
	private static $registerButton = 'RegisterView::Register';

	private $registerModel;
	private $loginModel;
	private $message = '';
	private $userName = '';

		public function __construct(LoginModel $loginModel, RegisterModel $regModel) {
		$this->loginModel = $loginModel;
		$this->registerModel = $regModel;
	}

	/**
	 * Create HTTP response
	 *
	 * Should be called after a register attempt has been determined
	 *
	 * @return  void BUT writes to standard output and cookies!
	 */
	public function response() {
	if (!$this->loginModel->isLoggedIn()) {
		$response = $this->generateRegisterFormHTML($this->message);
	} else {
		header('Location: ?'); // Redirect the user if they're attempting to access the register page while logged in
	}
		return $response;
	}
	
	/**
	* Generate HTML code of the 
	* @param $message, String output message
	* @return void, but writes to standard output!
	*/
	public function generateRegisterFormHTML($message) {
				return '
			<h2>Register new user</h2>
			<form method="post" > 
				<fieldset>
					<legend>Register a new user - Write username and password</legend>
					<p id="' . self::$regMessage . '">' . $message . '</p>
					
					<label for="' . self::$regUserName . '">Username :</label>
					<input type="text" id="' . self::$regUserName . '" name="' . self::$regUserName . '" value="' . $this->userName . '" />
					<br />
					<label for="' . self::$regPassword . '">Password :</label>
					<input type="password" id="' . self::$regPassword . '" name="' . self::$regPassword . '" />
					<br />
					<label for="' . self::$regPasswordRep . '">Repeat password :</label>
					<input type="password" id="' . self::$regPasswordRep . '" name="' . self::$regPasswordRep . '" />
					<br />
					<input type="submit" name="' . self::$registerButton . '" value="Register" />
				</fieldset>
			</form>
		';
	}

	/**
	 * Check for register attempt
	 *
	 * @return null if no attempt has been made, otherwise not null
	 */

	public function getRegisterAttempt() {
		if (isset($_POST[self::$registerButton])) {
			return $_POST[self::$registerButton];
		}
		return null;
	}

	public function getRequestUserName() {
		if (isset($_POST[self::$regUserName])) {
			$this->userName = $_POST[self::$regUserName];
			return $_POST[self::$regUserName];
		}
		return null;
	}

	public function getRequestPassword() {
		if (isset($_POST[self::$regPassword])) {
			return $_POST[self::$regPassword];
		}
		return null;
	}

	public function getRequestRepeatPassword() {
		if (isset($_POST[self::$regPasswordRep])) {
			return $_POST[self::$regPasswordRep];
		}
		return null;
	}

	public function setTooShortUsernameMessage() {
		$this->message = 'Username has too few characters, at least 3 characters.<br />';
	}

	public function setTooShortPasswordMessage() {
		$this->message .= 'Password has too few characters, at least 6 characters.<br />';
	}

	/**
	 * Clear the message
	 * 
	 * @return null
	 */

	public function clearMessage() {
		$this->message = '';
	}
	
}