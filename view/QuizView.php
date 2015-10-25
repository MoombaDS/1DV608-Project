<?php

/**
* A view class for displaying quizzes, quiz stats, and for taking them.
*
**/

class QuizView {
	private $quizModel;
	private static $quizViewString = "quiz";
	private static $startQuiz = "QuizView::Begin";
	private static $submittedQuiz = "QuizView::Submit";
	private static $question = "QuizView::Question_";
	private static $answer = "QuizView::Answer_";
	
	public function __construct(QuizModel $quizModel) {
		assert(!is_null($quizModel));
		$this->quizModel = $quizModel;
	}

	/**
	* Render the specifed quiz.
	* @param $quizName the name of the quiz to render.
	* @return null but writes to standard output.
	**/

	public function render($quizName) {
		if ($this->quizModel->quizExistsWithName($quizName)) {
		    echo '
		          <h1>' . $quizName . '</h1>
		          	';
		    $quizStats = $this->quizModel->getQuizStats($quizName, $this->quizModel->getLoggedInUser());

		    if ($quizStats->countQuizResults() < 1) {
		    	echo '
		    	<p>No one has taken the quiz yet...</p>
		    	';
		    } else {
		    	echo '<p>Recent quiz takers:</p>
		    		<ul>
		    	';
		    	foreach ($quizStats->getAllQuizResults() as $result) {
		    		echo '		<li><a href ="?user='. $result->getUserName() . '">' . $result->getUserName() . '</a> scored ' . $result->getScore() . ' out of ' . $this->quizModel->getQuiz($quizName)->getQuestionCount() . '</li>
		    		';
		    	}
		    	echo '</ul>';
		    }

		    if (!$this->quizModel->hasUserTakenQuiz($quizName, $this->quizModel->getLoggedInUser())
		    	&& !$this->quizModel->userIsCreator($quizName, $this->quizModel->getLoggedInUser())) {
		     	echo $this->generateBeginButtonHTML();
		    }

		    echo '
		          <div class="container">
		          <p>Return to <a href="?">home</a>?</p>
		          </div>
		         </body>
		      </html>
		    ';
		} else {
		    echo '
		          <h1>No such quiz exists!</h1>
		          <div class="container">
		              <p>Return to <a href="?">home</a>?</p>
		          </div>
		         </body>
		      </html>
		    ';
		}
	}

	/**
	* Render the questions for the specified quiz.
	* @param $quizName the name of the Quiz.
	* @return null but writes to standard output.
	**/

	public function renderQuizQuestions($quizName) {
		echo '
		          <h1>' . $quizName . '</h1>
	          	' . $this->createQuizForm($quizName) . '
	         </body>
	      </html>
	    ';
	}

	/**
	* Render the user's results for the quiz.
	* @param $result the Result object to be rendered.
	* @return null but writes to standard output.
	**/

	public function renderScore(Result $result) {
		$quizName = $this->requestingQuiz();
		$quiz = $this->quizModel->getQuiz($quizName);
		echo '
		          <h1>' . $quizName . ': Results</h1>
	          	<p>You scored: ' . $result->getScore() . ' out of ' . $quiz->getQuestionCount() . '</p>
	          	<p>Return to <a href="?">home</a>?</p>
	         </body>
	      </html>
	    ';
	}

	/**
	* Create the form for taking the quiz.
	* @param $quizName the name of the quiz being taken.
	* @return the HTML to render the form.
	**/

	private function createQuizForm($quizName) {
		$quiz = $this->quizModel->getQuiz($quizName);

		$string = '
			<form method="post" > 
				<fieldset>
					<legend>Please input your answers for each question!</legend>
					';

					for ($i = 0; $i < $quiz->getQuestionCount(); $i++) {
						$question = $quiz->getQuestion($i);
						$string .= '
						<label for="' . self::$question . $i . '">Question ' . ($i + 1) . ' : ' . $question->getQuestion() . '</label>
						<br />
						<label style="margin-left: 15px;" for="' . self::$answer . $i . '">Answer ' . ($i + 1) . ' :</label>
						<input type="text" id="' . self::$answer . $i . '" name="' . self::$answer . $i . '"/>
						<br />'
						;
					}

					$string .= '
						
					<input type="submit" name="' . self::$submittedQuiz . '" value="Submit" />
				</fieldset>
			</form>
		';
		return $string;
	}

	/**
	* Check for the quiz being requested by the user.
	* @return the name of the quiz being requested or null if no quiz is being requested.
	**/

	public function requestingQuiz() {
		if (isset($_GET[self::$quizViewString])) {
			return $_GET[self::$quizViewString];
		}
		return null;
	}

	/**
	* Check if the user has started a quiz.
	* @return true if they have, false if not.
	**/

	public function startedQuiz() {
		if (isset($_POST[self::$startQuiz])) {
			return true;
		}
		return false;
	}

	/**
	* Check if the user has submitted a quiz.
	* @return true if they have, false if not.
	**/

	public function submittedQuiz() {
		if (isset($_POST[self::$submittedQuiz])) {
			return true;
		}
		return false;
	}

	/**
	* Retreive the answers submitted by the user.
	* @return an array containing the answers or null if no answers exist.
	**/

	public function getAnswers() {
		if (isset($_POST[self::$submittedQuiz])) {
			$quiz = $this->quizModel->getQuiz($this->requestingQuiz());
			$answers = array();

			for ($i = 0; $i < $quiz->getQuestionCount(); $i++) {
				$currentAnswer = $_POST[self::$answer . $i];

				if (empty($currentAnswer)) {
					$answers[] = "";
				} else {
					$answers[] = $currentAnswer;
				}
				
			}

			return $answers;
		}
		return null;
	}

	/**
	* Generate the "begin" button for the quiz.
	* @return the HTML code for the begin button.
	**/

	private function generateBeginButtonHTML() {
		return '
			<form  method="post" >
				<p>Take the Test?</p>
				<input type="submit" name="' . self::$startQuiz . '" value="Begin!"/>
			</form>
		';
	}
}