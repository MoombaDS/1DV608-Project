<?php

class QuizListView {
	private $quizModel;
	private $loginView;
	private $dateTimeView;
	private static $quizListViewString = 'list';
	
	public function __construct(QuizModel $quizModel, LoginView $loginView, DateTimeView $dateTimeView) {
		assert(!is_null($quizModel));
		assert(!is_null($loginView));
		assert(!is_null($dateTimeView));
		$this->quizModel = $quizModel;
		$this->dateTimeView = $dateTimeView;
		$this->loginView = $loginView;
	}

	public function renderLanding() {

	}

	public function render() {
		echo '<!DOCTYPE html>
		      <html>
		        <head>
		          <meta charset="utf-8">
		          <title>Quiz List</title>
		        </head>
		        <body>
		          <h1>Quiz List</h1>
		          <div class="container">
		          <ul>
		          ';

		$fullQuizList = $this->quizModel->getLatestXQuizzes(-1);

		foreach ($fullQuizList as $quiz) {
			echo '<li><a href="/?quiz=' . $quiz->getName() . '">' . $quiz->getName() . '</a> by <a href="/?user=' . $quiz->getCreator() . '">' . $quiz->getCreator() . '</a></li>
				';
		}


		echo '
				</ul>' . $this->dateTimeView->show() . '
		          </div>
		         </body>
		      </html>
		    ';
	}

	public function wantsList() {
		if (isset($_GET[self::$quizListViewString])) {
			return true;
		}
		return false;
	}

}