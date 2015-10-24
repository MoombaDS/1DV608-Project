<?php

class QuizModel {
	private $quizDAL;

	public function __construct(QuizDAL $quizDAL) {
		assert(!is_null($quizDAL));
		$this->quizDAL = $quizDAL;
	}

	public function getLatestXQuizzes($x) {
		assert(is_integer($x));

		return $this->quizDAL->getLatestXQuizzes($x);
	}

	public function quizExistsWithName($quizName) {
		if ($this->quizDAL->getQuiz($quizName) != null) {
			return true;
		}
		return false;
	}

	public function userStatsExistFor($userName) {
		if ($this->quizDAL->userStatsExistFor($userName)) {
			return true;
		}
		return false;
	}

	public function getUserStatsFor(LoggedInUser $user) {
		return $this->quizDAL->getStatsForUser($user);
	}

	public function getQuiz($quizName) {
		return $this->quizDAL->getQuiz($quizName);
	}

	public function getQuizStats($quizName, $requestingUsername) {
		return new QuizStats($quizName, $requestingUsername, $this->quizDAL);
	}

	public function saveQuiz(Quiz $quiz) {
		assert(!is_null($quiz));
		assert($quiz->validateQuiz()); // Just to make absolutely sure the quiz is correct
		$this->quizDAL->saveQuiz($quiz);
		$this->quizDAL->updateUserCreationStats($quiz);
	}

	public function updateQuizStats(Result $result) {
		$user = new LoggedInUser($result->getUserName());
		assert(!$this->userIsCreator($result->getQuizName(), $user));
		assert(!$this->hasUserTakenQuiz($result->getQuizName(), $user));
		$this->quizDAL->updateQuizStats($result);
		$this->quizDAL->updateUserStats($result);
	}

	public function getLoggedInUser() {
		$user = $_SESSION[LoginModel::$sessionUserLocation];
		return $user;
	}

	public function hasUserTakenQuiz($quizName, LoggedInUser $user) {
		return $this->quizDAL->hasUserTakenQuiz($quizName, $user);
	}

	public function userIsCreator($quizName, LoggedInUser $user) {
		$quiz = $this->quizDAL->getQuiz($quizName);
		return (strcasecmp($user->getUserName(), $quiz->getCreator()) == 0);
	}
	
}