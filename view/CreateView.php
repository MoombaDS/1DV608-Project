<?php

/**
* A view class for display during Quiz creation.
*
**/

class CreateView {
	private static $createString = "create";

	private static $quizName = "CreateView::Name";
	private static $questionNo = "CreateView::QuestionNo";
	private static $messageId = "CreateView::Message";
	private static $submitInitial = "CreateView::SubmitInitial";
	private static $question = "CreateView::Question_";
	private static $answer = "CreateView::Answer_";
	private static $submitQuestions = "CreateView::SubmitQuestions";

	private static $sessionQuizLocation = "CreateView:CurrentQuiz";

	private $quizModel;

	private $message = "";
	private $chosenQuizName = "";
	private $questionsEntered = Array();
	private $answersEntered = Array();

	private $currentQuiz = null;
	
	public function __construct(QuizModel $quizModel) {
		assert(!is_null($quizModel));
		$this->quizModel = $quizModel;
	}

	/**
	* Render the quiz creation form.
	* @return null but writes to standard output.
	**/

	public function render() {
	    echo '
	    	  <h1>Create New Quiz</h1>
	          	' . $this->createForm() . '
	         </body>
	      </html>
	    ';
	}

	/**
	* Check if the user wants to create a quiz by looking at the GET values.
	* @return true if the user wants to create a quiz, false otherwise.
	**/

	public function wantsToCreate() {
		if (isset($_GET[self::$createString])) {
			return true;
		}
		return false;
	}

	/**
	* Check if the user has submitted the first part of the creation form.
	* @return true if they have, false otherwise.
	**/

	public function submittedInitialInfo() {
		if (isset($_POST[self::$submitInitial])) {
			return true;
		}
		return false;
	}

	/**
	* Check if the user has submitted the second part of the creation form.
	* @return true if they have, false otherwise.
	**/

	public function submittedQuestions() {
		if (isset($_POST[self::$submitQuestions])) {
			return true;
		}
		return false;
	}

	/**
	* Create and display the quiz creation form.
	* @return the HTML code for displaying the form.
	**/

	public function createForm() {
		$canContinue = false;
		$canFinish = false;
		$allQuestionsFilled = true; // Assume all questions are filled so if one is not we can make this false

		// Check for user input
		if ($this->submittedInitialInfo()) {
			// Validate user input
			$quizName = "";
			$questionCount = 0;
			if (isset($_POST[self::$quizName])) {
				$quizName = $_POST[self::$quizName];
			}

			if (isset($_POST[self::$questionNo])) {
				$questionCount = $_POST[self::$questionNo];
			}

			if (empty($quizName)) {
				$this->message .= "<p>No quiz name was entered.</p>";
			} else if ($this->quizModel->quizExistsWithName($quizName)) {
				$this->message .= "<p>A quiz with the specified name already exists.</p>";
			} else if (empty($questionCount)) {
				$this->chosenQuizName = $quizName;
				$this->message .= "<p>Please enter a number of questions.</p>";
			} else if ($questionCount < 1 || $questionCount > 30) { // The form code should handle this but just in case!
				$this->chosenQuizName = $quizName;
				$this->message .= "<p>Please enter a valid number of questions.</p>";
			} else {
				// If none of these checks fail, we can create a quiz and continue!
				$this->message = "";
				$canContinue = true;
				$this->currentQuiz = new Quiz($quizName, intval($questionCount), $this->quizModel->getLoggedInUser());
				$_SESSION[self::$sessionQuizLocation] = $this->currentQuiz;
				$this->questionsEntered = array_fill(0, $questionCount, "");
				$this->answersEntered = array_fill(0, $questionCount, "");
			}
		} else if ($this->submittedQuestions()) {
			// The user completed part two of the form so we should validate that info and add the questions to the quiz!
			$canContinue = true; // This variable remains true

			// Grab the current quiz from the session variable
			$this->currentQuiz = $_SESSION[self::$sessionQuizLocation];

			// Check to see if the quiz has already been created during this session (meaning that this is a POST resubmission)
			if ($this->currentQuiz->validateQuiz()) {
				return '
				<p>Quiz successfully created! You can access it <a href="?quiz=' . $this->currentQuiz->getName() . '">here</a>!</p>
				';
			}

			for ($i = 0; $i < $this->currentQuiz->getQuestionCount(); $i++) {
				// Check Question and Answer
				$currentQuestion = $_POST[self::$question . $i];
				$currentAnswer = $_POST[self::$answer . $i];

				if (empty($currentQuestion) || empty($currentAnswer)) {
					$allQuestionsFilled = false;
					if (empty($currentQuestion)) {
						$this->questionsEntered[$i] = "";
					} else {
						$this->questionsEntered[$i] = $currentQuestion;
					}

					if (empty($currentAnswer)) {
						$this->answersEntered[$i] = "";
					} else {
						$this->answersEntered[$i] = $currentAnswer;
					}
				} else {
					$this->questionsEntered[$i] = $currentQuestion;
					$this->answersEntered[$i] = $currentAnswer;
				}
			}

			if (!$allQuestionsFilled) {
				$this->message = "Please fill in all questions and answers.";
			} else {
				$canFinish = true;
			}
		}

		if (!$canContinue && !$canFinish) {
			return '
				<form method="post" > 
					<fieldset>
						<legend>Quiz Creation Part 1</legend>
						<p>Please enter a name for the quiz and the number of questions it will contain.</p>
						<p id="' . self::$messageId . '">' . $this->message . '</p>
						
						<label for="' . self::$quizName . '">Quiz Name :</label>
						<input type="text" id="' . self::$quizName . '" name="' . self::$quizName . '" value="' . $this->chosenQuizName . '" />
						<br />

						<label for="' . self::$questionNo . '">Number of Questions :</label>
						<input type="number" id="' . self::$questionNo . '" name="' . self::$questionNo . '" max="30" min="1" value="10" />
						<br />
						
						<input type="submit" name="' . self::$submitInitial . '" value="Submit" />
					</fieldset>
				</form>
			';
		} else if (!$canFinish) {
			$string = '
				<form method="post" > 
					<fieldset>
						<legend>Quiz Creation Part 2</legend>
						<p>Please input each question and corresponding answer.</p>
						<p id="' . self::$messageId . '">' . $this->message . '</p>
						';

						for ($i = 0; $i < $this->currentQuiz->getQuestionCount(); $i++) {
							$string .= '
							<label for="' . self::$question . $i . '">Question ' . ($i + 1) . ' :</label>
							<input type="text" id="' . self::$question . $i . '" name="' . self::$question . $i . '" value="'. $this->questionsEntered[$i] . '" />
							<br />
							<label style="margin-left: 15px;" for="' . self::$answer . $i . '">Answer ' . ($i + 1) . ' :</label>
							<input type="text" id="' . self::$answer . $i . '" name="' . self::$answer . $i . '" value="' . $this->answersEntered[$i] . '" />
							<br />'
							;
						}

						$string .= '
						
						<input type="submit" name="' . self::$submitQuestions . '" value="Submit" />
					</fieldset>
				</form>
			';
			return $string;
		} else {
			// The info entered was valid!
			// We can safely add all questions to the quiz!
			for ($i = 0; $i < $this->currentQuiz->getQuestionCount(); $i++) {
				$this->currentQuiz->addQuestion(new Question($this->questionsEntered[$i], $this->answersEntered[$i]));
			}

			// Make sure the quiz actually has the correct number of questions

			if (!$this->currentQuiz->validateQuiz()) {
				throw new Exception("An invalid number of questions was added to the quiz somehow...");
			}

			// Provide the user with a link to the Quiz Stat page!
			return '
				<p>Quiz successfully created! You can access it <a href="?quiz=' . $this->currentQuiz->getName() . '">here</a>!</p>
			';
			

		}
	}

	/**
	* Check for and retreive the created quiz.
	* @return null if no quiz has been created yet, the Quiz object if it has.
	**/

	public function checkForCompletedQuiz() {
		if (!is_null($this->currentQuiz)) {
			if ($this->currentQuiz->validateQuiz()) {
				return $this->currentQuiz;
			}
		}
		return null;
	}

}