<?php

/**
* The primary model for the Quiz system.
*
**/

class QuizModel {
	private $quizDAL;

	public function __construct(QuizDAL $quizDAL) {
		assert(!is_null($quizDAL));
		$this->quizDAL = $quizDAL;
	}

	/**
	* Retreive a list of the latest x quizzes in sorted order, with newest quizzes first.
	* @param $x the number of quizzes to retreive. -1 will retreive all quizzes.
	* @return a sorted array of Quiz objects with newest quizzes first.
	**/

	public function getLatestXQuizzes($x) {
		assert(is_integer($x));

		return $this->quizDAL->getLatestXQuizzes($x);
	}

	/**
	* Check to see whether a quiz with the given name exists.
	* @param $quizName the quiz name to check for.
	* @return true if it exists, false if not.
	**/

	public function quizExistsWithName($quizName) {
		if ($this->quizDAL->getQuiz($quizName) != null) {
			return true;
		}
		return false;
	}

	/**
	* Check to see if user stats exist for the specified username.
	* @param $userName the user to check for.
	* @return true if a stat file exists, false if not.
	**/

	public function userStatsExistFor($userName) {
		if ($this->quizDAL->userStatsExistFor($userName)) {
			return true;
		}
		return false;
	}

	/**
	* Method to retreive the stats for a given user.
	* @param $user a LoggedInUser object representing the user for which stats have been requested.
	* @return the UserStats object for the requested user.
	**/

	public function getUserStatsFor(LoggedInUser $user) {
		return $this->quizDAL->getStatsForUser($user);
	}

	/**
	* Retreive the quiz with the specified name.
	* @param $quizName the name of the requested quiz.
	* @return the Quiz object or null if it does not exist.
	**/

	public function getQuiz($quizName) {
		return $this->quizDAL->getQuiz($quizName);
	}

	/**
	* Retreive the stats for the given quiz based of the requesting user's identity.
	* @param $quizName the name of the quiz to retreive stats for.
	* @param $requestingUsername the user making the request.
	* @return a QuizStats object based off the parameters.
	**/

	public function getQuizStats($quizName, $requestingUsername) {
		return new QuizStats($quizName, $requestingUsername, $this->quizDAL);
	}

	/**
	* Save a newly created quiz so it can be accessed by others.
	* @param $quiz the valid Quiz object to be saved.
	* @throws an Exception if attempting to create a quiz when a quiz with that name already exists.
	* @return null
	**/

	public function saveQuiz(Quiz $quiz) {
		assert(!is_null($quiz));
		assert($quiz->validateQuiz()); // Just to make absolutely sure the quiz is correct
		$this->quizDAL->saveQuiz($quiz);
		$this->quizDAL->updateUserCreationStats($quiz);
	}

	/**
	* A function to update stats for a quiz based on a Result object.
	* @param $result a Result object representing a set of quiz results.
	* @return null.
	**/

	public function updateQuizStats(Result $result) {
		$user = new LoggedInUser($result->getUserName());
		assert(!$this->userIsCreator($result->getQuizName(), $user));
		assert(!$this->hasUserTakenQuiz($result->getQuizName(), $user));
		$this->quizDAL->updateQuizStats($result);
		$this->quizDAL->updateUserStats($result);
	}

	/**
	* A function to pull the currently logged in user from the session data.
	* @return a LoggedInUser object representing the user that is currently logged in.
	**/

	public function getLoggedInUser() {
		$user = $_SESSION[LoginModel::$sessionUserLocation];
		return $user;
	}

	/**
	* Check to see if a user has taken a given quiz.
	* @param $quizName the quiz in question.
	* @param $user a LoggedInUser object representing the user in question.
	* @return true if the user has taken the quiz, false otherwise.
	**/

	public function hasUserTakenQuiz($quizName, LoggedInUser $user) {
		return $this->quizDAL->hasUserTakenQuiz($quizName, $user);
	}

	/**
	* Check to see if the logged in user created the current quiz.
	* @param $quizName the quiz in question.
	* @param $user a LoggedInUser object representing the user in question.
	* @return true if the user created the quiz, false otherwise.
	**/

	public function userIsCreator($quizName, LoggedInUser $user) {
		$quiz = $this->quizDAL->getQuiz($quizName);
		return (strcasecmp($user->getUserName(), $quiz->getCreator()) == 0);
	}
	
}