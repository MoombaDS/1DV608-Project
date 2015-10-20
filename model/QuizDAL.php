<?php

class QuizDAL {
	private static $quizFilePath = "Quizzes/";
	private static $quizStatFilePath = "QuizStats/";
	private static $userStatFilePath = "UserStats/";

	public function getQuiz($quizName) {
		
	}

	public function saveQuiz(Quiz $quiz) {

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