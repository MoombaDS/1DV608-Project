<?php

class QuizStats {

	private $quizName;
	private $quizDAL;
	private $resultList = Array();

	public function __construct($quizName, LoggedInuser $requestingUser, $quizDAL) {
		assert(!empty($quizName));
		assert(!is_null($requestingUser));
		assert(!is_null($quizDAL));
		$this->quizName = $quizName;
		$this->quizDAL = $quizDAL;

		$this->resultList = $this->quizDAL->getStatsForQuiz($quizName, $requestingUser);
	}

	public function getQuizName() {
		return $this->quizName;
	}

	public function countQuizResults() {
		return count($this->resultList);
	}
	
	public function getAllQuizResults() {
		return $this->resultList;
	}

}