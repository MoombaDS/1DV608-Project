<?php

class QuizDAL {
	private static $quizFilePath = "Quizzes/";
	private static $quizStatFilePath = "QuizStats/";
	private static $userStatFilePath = "UserStats/";
	private static $separator = '_';

	public function getQuiz($quizName) {
		try {
			$fileContent = @file_get_contents(self::$quizFilePath . $quizName . '.quiz');
		} catch (Exception $e) {
			// No file found
			return null;
		}
		if ($fileContent !== FALSE) {
			return unserialize($fileContent);
		}
		return null;
	}

	public function saveQuiz(Quiz $quiz) {
		if ($this->getQuiz($quiz->getName()) != NULL) {
			throw new Exception('Cannot create a new Quiz with the same name as an existing quiz!');
		} else {
			$content = serialize($quiz);

			if (!file_exists(self::$quizFilePath)) {
    			mkdir(self::$quizFilePath, 0777, true);
			}

			file_put_contents(self::$quizFilePath . $quiz->getName() . '.quiz', $content);

			if (!file_exists(self::$quizStatFilePath)) {
    			mkdir(self::$quizStatFilePath, 0777, true);
			}

			// Each result will be stored as a single file in the folder for that individual quiz
			if (!file_exists(self::$quizStatFilePath . $quiz->getName())) {
				mkdir(self::$quizStatFilePath . $quiz->getName(), 0777, true);
			}
		}
	}

	public function getStatsForQuiz($quizName, LoggedInUser $requestUser) {
		assert(file_exists(self::$quizStatFilePath . $quizName));
		// Create an array containing all results if it's the owner of the quiz, if not just get the first five (most recent)
		$result = array();

		// We need to make sure we get the files in order of creation
	    $files = array();

	    foreach (scandir(self::$quizStatFilePath . $quizName) as $file) {
	    	if ($file[0] === '.') continue;
	        $files[$file] = filemtime(self::$quizStatFilePath . $quizName . '/' . $file);
	    }

	    arsort($files);
	    $files = array_keys($files);

	    $quiz = $this->getQuiz($quizName);
	    if (strcmp($quiz->getCreator(), $requestUser->getUserName()) == 0) {
	    	// We return the entire array of results
	    	foreach ($files as $file) {
				try {
					$fileContent = file_get_contents(self::$quizStatFilePath . $quizName . '/' . $file);
				} catch (Exception $e) {
					// No file found
					throw new Exception("No Such Quiz File Exists!");
				}
				if ($fileContent !== FALSE) {
					$result[] = unserialize($fileContent);
				}
	    	}
	    } else {
	    	for ($i = 0; $i < 5; $i++) {
	    		try {
					$fileContent = file_get_contents(self::$quizStatFilePath . $quizName . '/' . $files[$i]);
				} catch (Exception $e) {
					// No file found
					throw new Exception("No Such Quiz File Exists!");
				}
				if ($fileContent !== FALSE) {
					$result[] = unserialize($fileContent);
				}
	    	}
	    }
	    return $result;
	}

	public function getStatsForUser($userName, LoggedInUser $requestUser) {

	}

	public function hasUserTakenQuiz($quizName, LoggedInuser $user) {
		assert(file_exists(self::$quizStatFilePath . $quizName));

		$filesInFolder = scandir(self::$quizStatFilePath . $quizName);

		foreach ($filesInFolder as $fileName) {
			$parts = explode(self::$separator, $fileName);

			if (count($parts) == 2 && $parts[0] == $user->getUserName()) {
				return true;
			}
		}
		return false;
	}

	public function updateQuizStats(Result $result) {
		// Make sure the quiz actually exists
		assert(file_exists(self::$quizStatFilePath . $result->getQuizName()));
		$content = serialize($result);
		file_put_contents(self::$quizStatFilePath . $result->getQuizName() . '/' . $result->getUserName() . self::$separator . $result->getQuizName() . '.quizstats', $content);

	}

	public function updateUserStats($result) {

	}
}