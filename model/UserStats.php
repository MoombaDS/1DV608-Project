<?php

/**
* A class designed to store and represent user stats within the system.
**/

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

	/**
	* Add a quiz created by the user.
	* @param $quiz the quiz to add.
	* @return null.
	**/

	public function addQuiz(Quiz $quiz) {
		$this->quizzesCreated[] = $quiz->getName();
	}

	/**
	* Add a result for a quiz taken by the user.
	* @param $result the result to add.
	* @return null.
	**/

	public function addResult(Result $result) {
		assert(!is_null($result));
		$this->results[] = $result;
	}

	/**
	* Retreive all of the user's results, but only if the requesting user is the user themselves.
	* @param $requestingUser the user making the request.
	* @return an array of all the user's results if the requesting user is the user themselves, null otherwise.
	**/

	public function getResults(LoggedInUser $requestingUser) {
		if (strcasecmp($requestingUser->getUserName(), $this->userName) == 0) {
			// If the user requesting is the user in question
			return $this->results;
		}
		// Otherwise don't return any results.
		return null;
	}

	/**
	* Retreive a list of all quizzes created by the user.
	* @return an array containing the names of all quizzes created by the user.
	**/

	public function getQuizzesCreated() {
		return $this->quizzesCreated;
	}

}