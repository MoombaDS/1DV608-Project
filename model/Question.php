<?php

/**
* A class designed to represent a question within the system. Questions have both the question itself and the correct answer.
*
**/

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

	/**
	* Checks if the provided answer is correct
	* @param $answer the string answer provided by the user
	* @return true if the answer is correct (case insensitive), false otherwise
	**/

	public function isCorrectAnswer($answer) {
		if (strcasecmp($answer, $this->answer) == 0) {
			return true;
		}
		return false;
	}
}