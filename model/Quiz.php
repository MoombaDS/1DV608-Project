<?php

class Quiz {
	private $name;
	private $creator;
	private $questionCount;
	private $questions = array();

	public function __construct($name, $questionCount, $creator) {
		if ($name === NULL) {
			throw new Exception("Name cannot be null!");
		} else if ($creator === NULL) {
			throw new Exception("Creator cannot be null!");
		} else if ($questionCount === NULL || !is_integer($questionCount) || $questionCount < 1) {
			throw new Exception("Question count must exist!");
		}
		$this->name = $name;
		$this->creator = $creator;
		$this->questionCount = $questionCount;
	}

	public function getQuestionCount() {
		return $this->questionCount;
	}

	public function getName() {
		return $this->name;
	}

	public function getCreator() {
		return $this->creator;
	}

	public function addQuestion(Question $question) {
		assert(!is_null($question));
		if (count($this->questions) >= $this->questionCount) {
			throw new Exception("Cannot add more questions than initially specified!");
		}
		$this->questions[] = $question;
	}

	public function validateQuiz() {
		if (count($this->questions) == $this->questionCount) {
			return true;
		}
		return false;
	}

	public function getQuestion($questionNumber) {
		assert(is_integer($questionNumber));
		assert($questionNumber >= 0 && $questionNumber < count($this->questions));
		return ($this->questions[$questionNumber]);
	}
}