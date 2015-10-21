<?php

class QuizModel {
	private $quizDAL;

	public function __construct(QuizDAL $quizDAL) {
		assert(!is_null($quizDAL));
		$this->quizDAL = $quizDAL;
	}

	public function quizExistsWithName($quizName) {
		if ($this->quizDAL->getQuiz($quizName) != NULL) {
			return true;
		}
		return false;
	}

	public function saveQuiz(Quiz $quiz) {
		assert(!is_null($quiz));
		assert($quiz->validateQuiz()); // Just to make absolutely sure the quiz is correct
		$this->quizDAL->saveQuiz($quiz);
	}

	public function getLoggedInUser() {
		$user = $_SESSION[LoginModel::$sessionUserLocation];
		return $user->getUserName();
	}
	
}