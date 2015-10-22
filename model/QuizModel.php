<?php

class QuizModel {
	private $quizDAL;

	public function __construct(QuizDAL $quizDAL) {
		assert(!is_null($quizDAL));
		$this->quizDAL = $quizDAL;
	}

	public function quizExistsWithName($quizName) {
		if ($this->quizDAL->getQuiz($quizName) != null) {
			return true;
		}
		return false;
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
	}

	public function updateQuizStats(Result $result) {
		$this->quizDAL->updateQuizStats($result);
	}

	public function getLoggedInUser() {
		$user = $_SESSION[LoginModel::$sessionUserLocation];
		return $user;
	}

	public function hasUserTakenQuiz($quizName, LoggedInUser $user) {
		return $this->quizDAL->hasUserTakenQuiz($quizName, $user);
	}
	
}