<?php

class UserView {
	private $quizModel;
	private static $userViewString = 'user';
	
	public function __construct(QuizModel $quizModel) {
		assert(!is_null($quizModel));
		$this->quizModel = $quizModel;
	}

	public function render($userName) {
		if ($this->quizModel->userStatsExistFor($userName)) {
			echo '
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
			echo '
		          <h1>No such user stats exist!</h1>
		          <div class="container">
					<p>No stats could be found for user "' . $userName . '"...</p>
					<p>Return to <a href="/">home</a>?</p>
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