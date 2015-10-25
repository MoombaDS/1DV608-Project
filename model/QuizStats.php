<?php

/**
* A class designed for representing quiz stats.
*
**/

class QuizStats {

	private $quizName;
	private $quizDAL;
	/**
	* An array containg all Result obejcts associated with the current quiz.
	**/
	private $resultList = Array();

	public function __construct($quizName, LoggedInuser $requestingUser, $quizDAL) {
		assert(!empty($quizName));
		assert(!is_null($requestingUser));
		assert(!is_null($quizDAL));
		$this->quizName = $quizName;
		$this->quizDAL = $quizDAL;

		// Call the DAL in order to construct the stats for the quiz.
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