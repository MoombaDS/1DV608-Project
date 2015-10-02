<?php

class LoginView implements View {
	private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $cookieName = 'LoginView::CookieName';
	private static $cookiePassword = 'LoginView::CookiePassword';
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';
	private static $register = 'register';

	private static $regMessage = 'RegisterView::Message';
	private static $regUserName = 'RegisterView::UserName';
	private static $regPassword = 'RegisterView::Password';
	private static $regPasswordRep = 'RegisterView::PasswordRepeat';
	private static $registerButton = 'RegisterView::Register';

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
			if (isset($_POST[self::$keep]) && $_POST[self::$keep]) {
				setcookie(self::$cookieName, $_POST[self::$name], time()+60*60*24*30);
				$token = $this->loginModel->generateAndSaveToken($_POST[self::$name]);
				setcookie(self::$cookiePassword, $token, time()+60*60*24*30);
			} else if (isset($_COOKIE[self::$cookieName])) {
				// If it was a successful login with cookies we want to generate a new cookie password
				$token = $this->loginModel->generateAndSaveToken($_COOKIE[self::$cookieName]);
				setcookie(self::$cookiePassword, $token, time()+60*60*24*30);
			}
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

	/**
	 * Check for login attempt
	 *
	 * @return null if no attempt has been made, otherwise not null
	 */

	public function getLoginAttempt() {
		if (isset($_POST[self::$login])) {
			return $_POST[self::$login];
		}
		return null;
	}

	/**
	 * Check for cookies
	 *
	 * @return null if no cookies exist, a Model/User containing the cookie information if they do
	 */

	public function getCookies() {
		if (isset($_COOKIE[self::$cookieName])) {
			return new User($_COOKIE[self::$cookieName], $_COOKIE[self::$cookiePassword]);
		}
		return null;
	}

	/**
	 * Check for logout attempt
	 *
	 * @return null if no attempt has been made, otherwise not null
	 */

	public function getLogOut() {
		if (isset($_POST[self::$logout])) {
			return $_POST[self::$logout];
		}
		return null;
	}
	
	/**
	 * Get the username submitted by the user
	 *
	 * @return the username
	 */

	public function getRequestUserName() {
		if (isset($_POST[self::$name])) {
			return $_POST[self::$name];
		}
		return null;
	}

	/**
	 * Get the password submitted by the user
	 *
	 * @return the password
	 */

	public function getRequestPassword() {
		if (isset($_POST[self::$password])) {
			return $_POST[self::$password];
		}
		return null;
	}

	/**
	 * Set the message to reflect that username is missing
	 *
	 * @return null
	 */

	public function setUserNameMissingMessage() {
		$this->message = 'Username is missing';
	}

	/**
	 * Set the message to reflect that password is missing
	 * 
	 * @param $username, the username used in the attmept
	 * @return null
	 */

	public function setPasswordMissingMessage($username) {
		$this->userName = $username;
		$this->message = 'Password is missing';
	}

	/**
	 * Set the message to reflect that username or password was wrong
	 * 
	 * @param $username, the username used in the attmept
	 * @return null
	 */

	public function setWrongUserNameOrPasswordMessage($username) {
		$this->userName = $username;
		$this->message = 'Wrong name or password';
	}

	/**
	 * Welcome the user after a successful log in attempt
	 * 
	 * @return null
	 */

	public function setWelcomeMessage() {
		if (isset($_POST[self::$keep])) {
			$this->message = 'Welcome and you will be remembered';
		} else {
			$this->message = 'Welcome';
		}
	}

	/**
	 * Welcome the user after a successful cookie log in
	 * 
	 * @return null
	 */

	public function setCookieWelcomeMessage() {
		$this->message = 'Welcome back with cookie';
	}

	/**
	 * Alert the user that their cookies were tampered with and remove said cookies
	 * 
	 * @return null
	 */

	public function setFailedCookieMessage() {
		$this->message = 'Wrong information in cookies';
		setcookie(self::$cookieName, "", time()-3600);
		setcookie(self::$cookiePassword, "", time()-3600);
	}

	/**
	 * Bid farewell to the user on log out and remove any cookies
	 * 
	 * @return null
	 */

	public function setLogOutMessageAndClearCookies() {
		$this->message = 'Bye bye!';
		if (isset($_COOKIE[self::$cookieName])) {
			setcookie(self::$cookieName, "", time()-3600);
			setcookie(self::$cookiePassword, "", time()-3600);
		}
	}

	/**
	 * Clear the message
	 * 
	 * @return null
	 */

	public function clearMessage() {
		$this->message = '';
	}

	public function shouldMoveToRegisterPage() {
		if (isset($_GET[self::$register])) {
			return true;
		}
		return false;
	}
	
}