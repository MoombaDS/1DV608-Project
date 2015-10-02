<?php

class LoginController {
	private $model;
	private $regModel;
	private $view;
	private $regView;
	private $layoutView;
	private $dateTimeView;

	public function __construct(LoginView $view, RegisterView $regView, LayoutView $layoutView, DateTimeView $dateTimeView, LoginModel $model, RegisterModel $regModel) {
		$this->model = $model;
		$this->regModel = $regModel;
		$this->view = $view;
		$this->layoutView = $layoutView;
		$this->dateTimeView = $dateTimeView;
		$this->regView = $regView;
	}

	/**
	 * Begin execution of the log in system and draw the layout view
	 * 
	 * @return null
	 */

	public function begin() {
		$this->checkForLogInOrLogOut();

		if (!$this->model->isLoggedIn()) { // Only check for a registration attempt if logged out
			$this->checkForRegisterAttempt();
		}

		if ($this->view->shouldMoveToRegisterPage()) {
			$this->layoutView->render($this->model->isLoggedIn(), $this->view->shouldMoveToRegisterPage(), $this->regView, $this->dateTimeView);
		} else {
			$this->layoutView->render($this->model->isLoggedIn(), $this->view->shouldMoveToRegisterPage(), $this->view, $this->dateTimeView);
		}
	}

	/**
	 * Check to see if there has been a log in or log out attempt, and also whether any cookies exist
	 * 
	 * @return null
	 */

	private function checkForLogInOrLogOut() {
		$loginAttempt = $this->view->getLoginAttempt();
		$logout = $this->view->getLogOut();
		$cookieUser = $this->view->getCookies();
		if (!is_null($cookieUser) && !$this->model->isLoggedIn()) {
			// We have cookies

			$validCookie = $this->model->validateCookieAndLogIn($cookieUser);

			if (!$validCookie) {
				$this->view->setFailedCookieMessage();
			} else {
				$this->view->setCookieWelcomeMessage();
			}

		} else if (!is_null($loginAttempt) && !$this->model->isLoggedIn()) {
			// There was a login attempt

			$userName = $this->view->getRequestUserName();
			if (empty($userName)) {
				$this->view->setUserNameMissingMessage();
				return;
			}
			$password = $this->view->getRequestPassword();
			if (empty($password)) {
				$this->view->setPasswordMissingMessage($userName);
				return;
			}

			// Check the credentials
			$result = $this->model->validateCredentialsAndLogIn(new User($userName, $password));

			if (!$result) {
				$this->view->setWrongUserNameOrPasswordMessage($userName);
			} else {
				$this->view->setWelcomeMessage();
			}

		} else if (!is_null($logout)) {
			$loggedOut = $this->model->logOut();
			if ($loggedOut) {
				$this->view->setLogOutMessageAndClearCookies();
			}
		} else {
			$this->view->clearMessage();
		}
	}

	private function checkForRegisterAttempt() {
		$registerAttempt = $this->regView->getRegisterAttempt();

		if (!is_null($registerAttempt)) {
			// User wants to try and register
			$canRegister = true;

			// Input validation!
			$userName = $this->regView->getRequestUserName();
			if (empty($userName) || strlen($userName) < 3) {
				$this->regView->setTooShortUsernameMessage();
				$canRegister = false;
			}
			// TODO Check for illegal characters

			$password = $this->regView->getRequestPassword();
			if (empty($password) || strlen($password) < 6) {
				$this->regView->setTooShortPasswordMessage();
				$canRegister = false;
			}
		}
	}
	
}