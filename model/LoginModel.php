<?php

class LoginModel {
	private $userName = "Admin";
	private $password = "Password";
	//private $loggedIn = false;
	
	public function validateCredentials($userName, $password) {
		return ($password == $this->password && $userName == $this->userName);
	}

}