<?php

class QuizDAL {
	private static $quizFilePath = "Quizzes/";
	private static $quizStatFilePath = "QuizStats/";
	private static $userStatFilePath = "UserStats/";

	public function getQuiz($quizName) {
		
	}

	public function saveQuiz(Quiz $quiz) {
		if ($this->getQuiz($quiz->getName()) != NULL) {
			throw new Exception('Cannot create a new Quiz with the same name as an existing quiz!');
		} else {
			$content = serialize($quiz);
			$statFile = new QuizStats($quiz->getName());
			$statContent = serialize($statFile);

			if (!file_exists(self::$quizFilePath)) {
    			mkdir(self::$quizFilePath, 0777, true);
			}

			file_put_contents(self::$quizFilePath . $quiz->getName() . '.quiz', $content);

			if (!file_exists(self::$quizStatFilePath)) {
    			mkdir(self::$quizStatFilePath, 0777, true);
			}

			file_put_contents(self::$quizStatFilePath . $quiz->getName() . '.quizstats', $content);
		}
	}

	public function getStatsForQuiz($quizName, $requestUsername) {

	}

	public function getStatsForUser($userName, $requestUsername) {

	}

	public function updateQuizStats($quizName, $userName, $score) {

	}

	public function updateUserStats($userName, $quizName, $score) {

	}
}