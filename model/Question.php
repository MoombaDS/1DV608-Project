<?php

class Question {
	private $question;
	private $answer;
	
	public function __construct($question, $answer) {
		if ($question === NULL) {
			throw new Exception("Question cannot be empty!");
		} else if ($answer === NULL) {
			throw new Exception("Answer cannot be empty!");
		}
		$this->question = $question;
		$this->answer = $answer;
	}

	public function getQuestion() {
		return $this->question;
	}

	public function getAnswer() {
		return $this->answer;
	}

	public function isCorrectAnswer($answer) {
		if (strcasecmp($answer, $this->answer) == 0) {
			return true;
		}
		return false;
	}
}