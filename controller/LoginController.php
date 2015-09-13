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

	public function begin() {
		$this->checkForLogInOrLogOut();

		$this->layoutView->render($this->model->isLoggedIn(), $this->view, $this->dateTimeView);
	}

	private function checkForLogInOrLogOut() {
		$loginAttempt = $this->view->getLoginAttempt();
		$logout = $this->view->getLogOut();
		if (!is_null($loginAttempt) && !$this->model->isLoggedIn()) {
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

			$result = $this->model->validateCredentialsAndLogIn(new User($userName, $password));

			if (!$result) {
				$this->view->setWrongUserNameOrPasswordMessage($userName);
			} else {
				$this->view->setWelcomeMessage();
			}

		} else if (!is_null($logout)) {
			$loggedOut = $this->model->logOut();
			if ($loggedOut) {
				$this->view->setLogOutMessage();
			}
		} else {
			$this->view->clearMessage();
		}
	}
	
}