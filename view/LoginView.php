<?php

class LoginView {
	private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $cookieName = 'LoginView::CookieName';
	private static $cookiePassword = 'LoginView::CookiePassword';
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';

	private $loginModel;
	private $message = '';
	private $userName = '';

	public function __construct(LoginModel $model) {
		$this->loginModel = $model;
	}

	/**
	 * Create HTTP response
	 *
	 * Should be called after a login attempt has been determined
	 *
	 * @return  void BUT writes to standard output and cookies!
	 */
	public function response() {
	if (!$this->loginModel->isLoggedIn()) {
		$response = $this->generateLoginFormHTML($this->message);
	} else {
		$response = $this->generateLogoutButtonHTML($this->message);
	}
		return $response;
	}

	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLogoutButtonHTML($message) {
		return '
			<form  method="post" >
				<p id="' . self::$messageId . '">' . $message .'</p>
				<input type="submit" name="' . self::$logout . '" value="logout"/>
			</form>
		';
	}
	
	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLoginFormHTML($message) {
		return '
			<form method="post" > 
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$messageId . '">' . $message . '</p>
					
					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . $this->userName . '" />

					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />

					<label for="' . self::$keep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" />
					
					<input type="submit" name="' . self::$login . '" value="login" />
				</fieldset>
			</form>
		';
	}

	public function getLoginAttempt() {
		if (isset($_POST[self::$login])) {
			return $_POST[self::$login];
		}
		return null;
	}

	public function getLogOut() {
		if (isset($_POST[self::$logout])) {
			return $_POST[self::$logout];
		}
		return null;
	}
	
	//CREATE GET-FUNCTIONS TO FETCH REQUEST VARIABLES
	public function getRequestUserName() {
		if (isset($_POST[self::$name])) {
			return $_POST[self::$name];
		}
		return null;
		//RETURN REQUEST VARIABLE: USERNAME
	}

	public function getRequestPassword() {
		if (isset($_POST[self::$password])) {
			return $_POST[self::$password];
		}
		return null;
	}

	public function setUserNameMissingMessage() {
		$this->message = 'Username is missing';
	}

	public function setPasswordMissingMessage($username) {
		$this->userName = $username;
		$this->message = 'Password is missing';
	}

	public function setWrongUserNameOrPasswordMessage($username) {
		$this->userName = $username;
		$this->message = 'Wrong name or password';
	}

	public function setWelcomeMessage() {
		$this->message = 'Welcome';
	}

	public function setLogOutMessage() {
		$this->message = 'Bye bye!';
	}

	public function clearMessage() {
		$this->message = '';
	}
	
}