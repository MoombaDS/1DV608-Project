<?php

class QuizView {
	private $quizModel;
	private $dateTimeView;
	private static $quizViewString = "quiz";
	private static $startQuiz = "QuizView::Begin";
	private static $submittedQuiz = "QuizView::Submit";
	private static $question = "QuizView::Question_";
	private static $answer = "QuizView::Answer_";
	
	public function __construct(QuizModel $quizModel, DateTimeView $dateTimeView) {
		assert(!is_null($quizModel));
		assert(!is_null($dateTimeView));
		$this->quizModel = $quizModel;
		$this->dateTimeView = $dateTimeView;
	}

	public function render($quizName) {
		if ($this->quizModel->quizExistsWithName($quizName)) {
		    echo '<!DOCTYPE html>
		      <html>
		        <head>
		          <meta charset="utf-8">
		          <title>' . $quizName . '</title>
		        </head>
		        <body>
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
		    		echo '		<li><a href ="/?user='. $result->getUserName() . '">' . $result->getUserName() . '</a> scored ' . $result->getScore() . ' out of ' . $this->quizModel->getQuiz($quizName)->getQuestionCount() . '</li>
		    		';
		    	}
		    	echo '</ul>';
		    }

		    if (!$this->quizModel->hasUserTakenQuiz($quizName, $this->quizModel->getLoggedInUser())) { // TODO add so creator can't take quiz
		     	echo $this->generateBeginButtonHTML();
		    }

		    echo '
		          <div class="container">
		          <p>Return to <a href="/">home</a>?</p>
		              ' . $this->dateTimeView->show() . '
		          </div>
		         </body>
		      </html>
		    ';
		} else {
		    echo '<!DOCTYPE html>
		      <html>
		        <head>
		          <meta charset="utf-8">
		          <title>No such quiz!</title>
		        </head>
		        <body>
		          <h1>No such quiz exists!</h1>
		          <div class="container">
		              <p>Return to <a href="/">home</a>?</p>
		              ' . $this->dateTimeView->show() . '
		          </div>
		         </body>
		      </html>
		    ';
		}
	}

	public function renderQuizQuestions($quizName) {
		echo '<!DOCTYPE html>
	      <html>
	        <head>
	          <meta charset="utf-8">
	          <title>' . $quizName . '</title>
		        </head>
		        <body>
		          <h1>' . $quizName . '</h1>
	          	' . $this->createQuizForm($quizName) . '
	          <div class="container">
	              ' . $this->dateTimeView->show() . '
	          </div>
	         </body>
	      </html>
	    ';
	}

	public function renderScore(Result $result) {
		$quizName = $this->requestingQuiz();
		$quiz = $this->quizModel->getQuiz($quizName);
		echo '<!DOCTYPE html>
	      <html>
	        <head>
	          <meta charset="utf-8">
	          <title>' . $quizName . '</title>
		        </head>
		        <body>
		          <h1>' . $quizName . ': Results</h1>
	          	<p>You scored: ' . $result->getScore() . ' out of ' . $quiz->getQuestionCount() . '</p>
	          	<p>Return to <a href="/">home</a>?</p>
	          <div class="container">
	              ' . $this->dateTimeView->show() . '
	          </div>
	         </body>
	      </html>
	    ';
	}

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

	public function requestingQuiz() {
		if (isset($_GET[self::$quizViewString])) {
			return $_GET[self::$quizViewString];
		}
		return null;
	}

	public function startedQuiz() {
		if (isset($_POST[self::$startQuiz])) {
			return true;
		}
		return false;
	}

	public function submittedQuiz() {
		if (isset($_POST[self::$submittedQuiz])) {
			return true;
		}
		return false;
	}

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

	private function generateBeginButtonHTML() {
		return '
			<form  method="post" >
				<p>Take the Test?</p>
				<input type="submit" name="' . self::$startQuiz . '" value="Begin!"/>
			</form>
		';
	}
}