<?php

class QuizStats {

	private $quizName;

	public function __construct($quizName) {
		assert(!empty($quizName));
		$this->quizName = $quizName;
	}

	public function getQuizName() {
		return $this->quizName;
	}
	
}