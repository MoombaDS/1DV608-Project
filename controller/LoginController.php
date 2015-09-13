<?php

class LoginController {
	private $model;
	private $view;
	private $layoutView;
	private $dateTimeView;

	public function __construct(LoginView $view, LayoutView $layoutView, DateTimeView $dateTimeView, LoginModel $model) {
		$this->model = $model;
		$this->view = $view;
		$this->layoutView = $layoutView;
		$this->dateTimeView = $dateTimeView;
	}

	/**
	 * Begin execution of the log in system and draw the layout view
	 * 
	 * @return null
	 */

	public function begin() {
		$this->checkForLogInOrLogOut();

		$this->layoutView->render($this->model->isLoggedIn(), $this->view, $this->dateTimeView);
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
	
}