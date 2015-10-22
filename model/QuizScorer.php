<?php

class QuizScorer {
	
	public function scoreQuiz(Quiz $quiz, LoggedInUser $user, $answers) {
		assert(!is_null($quiz));
		assert(!is_null($user));
		assert(is_array(($answers)));

		$score = 0;

		if ($quiz->getQuestionCount() != count($answers)) {
			throw new Exception("Number of answers in supplied array differs from number of questions in the quiz!");
		}

		for ($i = 0; $i < $quiz->getQuestionCount(); $i++) {
			if (strcasecmp($quiz->getQuestion($i)->getAnswer(), $answers[$i]) == 0) {
				$score++;
			}
		}

		return new Result($user, $quiz, $score);
	}

}