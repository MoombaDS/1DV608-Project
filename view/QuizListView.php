<?php

class QuizListView {
	private $quizModel;
	private static $quizListViewString = 'list';
	
	public function __construct(QuizModel $quizModel) {
		assert(!is_null($quizModel));
		$this->quizModel = $quizModel;
	}

	public function renderLanding() {
		echo '
				<h1>Recent Quizzes</h1>
				<ul>';

		$latestFiveQuizzes = $this->quizModel->getLatestXQuizzes(5);

		if (count($latestFiveQuizzes) > 0) {
			foreach ($latestFiveQuizzes as $quiz) {
				echo '<li><a href="/?quiz=' . $quiz->getName() . '">' . $quiz->getName() . '</a> by <a href="/?user=' . $quiz->getCreator() . '">' . $quiz->getCreator() . '</a></li>
					';
			}
			echo '</ul>
				  <div class="container">
					<p><a href="/?list">View all quizzes</a></p>';
		} else {
			echo '<li>No quizzes yet...</li>
				</ul>
				<div class="container">';
		}

		echo '
					<p><a href="/?create">Create new quiz</a></p>
		          </div>
		         </body>
		      </html>
		    ';
	}

	public function render() {
		echo '
		          <h1>Quiz List</h1>
		          <div class="container">
		          <ul>
		          ';

		$fullQuizList = $this->quizModel->getLatestXQuizzes(-1);

		if (count($fullQuizList) > 0) {
			foreach ($fullQuizList as $quiz) {
				echo '<li><a href="/?quiz=' . $quiz->getName() . '">' . $quiz->getName() . '</a> by <a href="/?user=' . $quiz->getCreator() . '">' . $quiz->getCreator() . '</a></li>
					';
			}
		} else {
			echo '<li>No quizzes yet...</li>';
		}


		echo '
				</ul>
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