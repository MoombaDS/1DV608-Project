<?php

/**
* A representation of a Quiz within the system. Stores all relevant information.
*
**/

class Quiz {
	/**
	* The name of the quiz.
	**/
	private $name;

	/**
	* The username of the creator.
	**/
	private $creator;

	/**
	* The number of questions in the quiz.
	**/
	private $questionCount;

	/**
	* An array of Question objects.
	**/
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
		$this->creator = $creator->getUserName();
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

	/**
	* Adds a question to the Quiz while simultaneously checking to ensure that the number of questions.
	* does not exceed the number stated in the quiz.
	* @param $question the Question object to be added.
	* @return null
	* @throws an Exception if adding the question will cause the number of questions to exceed the stated limit.
	**/
	public function addQuestion(Question $question) {
		assert(!is_null($question));
		if (count($this->questions) >= $this->questionCount) {
			throw new Exception("Cannot add more questions than initially specified!");
		}
		$this->questions[] = $question;
	}

	/**
	* A method designed to be called once a Quiz has been completely created to ensure that the number of questions
	* added matches the number stated in the question count.
	* @return true if the number of questions added is the same as the number stated in the question count.
	**/

	public function validateQuiz() {
		if (count($this->questions) == $this->questionCount) {
			return true;
		}
		return false;
	}

	/**
	* A method for accessing each question by its number.
	* @param integer representing the number of the question (-1).
	* @return the designated Question object.
	**/

	public function getQuestion($questionNumber) {
		assert(is_integer($questionNumber));
		assert($questionNumber >= 0 && $questionNumber < count($this->questions));
		return ($this->questions[$questionNumber]);
	}
}