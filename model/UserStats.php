<?php

class UserStats {
	private $userName;
	private $results = array();
	private $quizzesCreated = array();

	public function __construct(LoggedInUser $user) {
		assert(!is_null($user));
		$this->userName = $user->getUserName();
	}

	public function getUserName() {
		return $this->userName;
	}

	public function addQuiz(Quiz $quiz) {
		$this->quizzesCreated[] = $quiz->getName();
	}

	public function addResult(Result $result) {
		assert(!is_null($result));
		$this->results[] = $result;
	}

	public function getResults(LoggedInUser $requestingUser) {
		if (strcasecmp($requestingUser->getUserName(), $this->userName) == 0) {
			// If the user requesting is the user in question
			return $this->results;
		}
		// Otherwise don't return any results.
		return null;
	}

	public function getQuizzesCreated() {
		return $this->quizzesCreated;
	}

}