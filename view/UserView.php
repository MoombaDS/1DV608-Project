<?php

class UserView {
	private $quizModel;
	private $dateTimeView;
	private static $userViewString = 'user';
	
	public function __construct(QuizModel $quizModel, DateTimeView $dateTimeView) {
		assert(!is_null($quizModel));
		assert(!is_null($dateTimeView));
		$this->quizModel = $quizModel;
		$this->dateTimeView = $dateTimeView;
	}

	public function render($userName) {
		echo '<!DOCTYPE html>
		      <html>
		        <head>
		          <meta charset="utf-8">
		          ';
		if ($this->quizModel->userStatsExistFor($userName)) {
			echo '<title>' . $userName . ': Stats</title>
		        </head>
		        <body>
		          <h1>' . $userName . ': Stats</h1>
		          	<div class="container">
		          	';

		 	$userStats = $this->quizModel->getUserStatsFor(new LoggedInUser($userName));

		 	$quizResults = $userStats->getResults($this->quizModel->getLoggedInUser());

		 	if (!is_null($quizResults)) {
		 		echo '<h2>Recently Taken Quizzes</h2>
		    		<ul>
		    	';
		    	for ($i = count($quizResults) - 1; $i >= 0; $i--) {
		    		echo '
		    		<li>Scored ' . $quizResults[$i]->getScore() . ' out of ' . $this->quizModel->getQuiz($quizResults[$i]->getQuizName())->getQuestionCount() . ' on <a href="/?quiz=' . $quizResults[$i]->getQuizName() . '">' . $quizResults[$i]->getQuizName() . '</a></li>
		    		';
		    	}
		    	echo '</ul>';
		 	}

		    echo '
		    	<h2>Quizzes Created</h2>';

		    $quizzesCreated = $userStats->getQuizzesCreated();

		    if (count($quizzesCreated) < 1) {
		    	echo '
		    	<p>No quizzes created so far!';
		    } else {
		    	echo '
		    		<ul>';
		    	for ($i = count($quizzesCreated) - 1; $i >= 0; $i--) {
		    		echo '
		    			<li><a href="/?quiz=' . $quizzesCreated[$i] . '">' . $quizzesCreated[$i] . '</a></li>';
		    	}
		    	echo '
		    		</ul>';
		    }

		    echo '</div>
		         </body>
		      </html>';
		} else {
			echo '<title>No such user stats exist!</title>
		        </head>
		        <body>
		          <h1>No such user stats exist!</h1>
		          <div class="container">
					<p>No stats could be found for user "' . $userName . '"...</p>
					<p>Return to <a href="/">home</a>?</p>
					' . $this->dateTimeView->show() . '
		          </div>
		         </body>
		      </html>';
		}
	}

	public function requestingUser() {
		if (isset($_GET[self::$userViewString])) {
			return $_GET[self::$userViewString];
		}
		return null;
	}
}