<?php

class LoginController {
	private $model;
	private $view;

	public function __construct(LoginView $view, LoginModel $model) {
		$this->model = $model;
		$this->view = $view;
	}

	public function doLogIn() {
		$loginAttempt = $this->view->getLoginAttempt();
		if (!is_null($loginAttempt)) {
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

			$result = $this->model->validateCredentials($userName, $password);

			if (!$result) {
				$this->view->setWrongUserNameOrPasswordMessage($userName);
			}

		} else {
			$this->view->clearMessage();
		}
	}
	
}