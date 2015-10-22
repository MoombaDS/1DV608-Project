<?php

class Result {

	private $userName;
	private $quizName;
	private $score;
	
	public function __construct(LoggedInUser $user, Quiz $quiz, $score) {
		assert(!is_null($user));
		assert(!is_null($quiz));
		assert(is_integer($score));
		$this->userName = $user->getUserName();
		$this->quizName = $quiz->getName();
		$this->score = $score;
	}

	public function getUserName() {
		return $this->userName;
	}

	public function getQuizName() {
		return $this->quizName;
	}

	public function getScore() {
		return $this->score;
	}
}