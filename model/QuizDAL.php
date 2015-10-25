<?php

/**
* A data access layer for storing and retrieving all data related to quizzes. With more time and access to databases, this would likely be
* implemented through the use of databases such as MySQL. However, lacking these things, it is instead implemented using files.
*
* File paths should ideally be located outside the hierarchy accessible through the server.
*
* Methods should preferably be accessed via the QuizModel which acts as a facade of sorts.
**/

class QuizDAL {
	private static $quizFilePath = "Quizzes/";
	private static $quizStatFilePath = "QuizStats/";
	private static $userStatFilePath = "UserStats/";
	private static $userStatSuffix = ".userstats";
	private static $separator = '_';

	/**
	* Retreive the quiz with the specified name.
	* @param $quizName the name of the requested quiz.
	* @return the Quiz object or null if it does not exist.
	**/

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

	/**
	* Retreive a list of the latest x quizzes in sorted order, with newest quizzes first.
	* @param $x the number of quizzes to retreive. -1 will retreive all quizzes.
	* @return a sorted array of Quiz objects with newest quizzes first.
	**/

	public function getLatestXQuizzes($x) {
		assert(is_integer($x));

		// If x is -1 return all, else return the specified number;

		$result = array();

		// If the folder doesn't even exist we can assume there are none and just return the empty array
		if (!file_exists(self::$quizFilePath)) {
    		return $result;
		}

		$files = array();

		foreach (scandir(self::$quizFilePath) as $file) {
	    	if ($file[0] === '.') continue;
	        $files[$file] = filemtime(self::$quizFilePath . '/' . $file);
	    }

	    arsort($files);
	    $files = array_keys($files);

	    $allQuizzes = array();

	    foreach ($files as $file) {
			try {
				$fileContent = file_get_contents(self::$quizFilePath . '/' . $file);
			} catch (Exception $e) {
				// No file found
				throw new Exception("No Such Quiz File Exists!");
			}
			if ($fileContent !== FALSE) {
				$allQuizzes[] = unserialize($fileContent);
			}
	    }

	    // If x is -1 return the $allQuizzes variable
	    if ($x < 0) {
	    	return $allQuizzes;
	    } else {
	    	for ($i = 0; $i < $x; $i++) {
	    		if ($i >= count($allQuizzes)) {
	    			break;
	    		}
	    		$result[] = $allQuizzes[$i];
	    	}
	    	return $result;
	    }
	}

	/**
	* Save a newly created quiz so it can be accessed by others.
	* @param $quiz the valid Quiz object to be saved.
	* @throws an Exception if attempting to create a quiz when a quiz with that name already exists.
	* @return null
	**/

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

	/**
	* Retreives the stats for a given quiz.
	* @param $quizName the name of the quiz.
	* @param $requestUser a LoggedInUser object representing the user making the request.
	* @return an array of Result objects. If the user is the creator of the quiz, then all results will be returned.
	* If not, then only the most recent five will be returned.
	**/

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
	    		if ($i >= count($files)) { // If there are fewer files than five we just break the cycle
	    			break;
	    		}
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

	/**
	* Check to see if user stats exist for the specified username.
	* @param $userName the user to check for.
	* @return true if a stat file exists, false if not.
	**/

	public function userStatsExistFor($userName) {
		return file_exists(self::$userStatFilePath . $userName . self::$userStatSuffix);
	}

	/**
	* Method to retreive the stats for a given user.
	* @param $user a LoggedInUser object representing the user for which stats have been requested.
	* @return the UserStats object for the requested user.
	**/

	public function getStatsForUser(LoggedInUser $user) {
		try {
				$fileContent = file_get_contents(self::$userStatFilePath . $user->getUserName() . self::$userStatSuffix);
			} catch (Exception $e) {
				// No file found
				throw new Exception("No Such Quiz File Exists!");
			}
			if ($fileContent !== FALSE) {
				return unserialize($fileContent);
			}
	}

	/**
	* Check to see if a user has taken a given quiz.
	* @param $quizName the quiz in question.
	* @param $user a LoggedInUser object representing the user in question.
	* @return true if the user has taken the quiz, false otherwise.
	**/

	public function hasUserTakenQuiz($quizName, LoggedInUser $user) {
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

	/**
	* A function to update stats for a quiz based on a Result object.
	* @param $result a Result object representing a set of quiz results.
	* @return null.
	**/

	public function updateQuizStats(Result $result) {
		// Make sure the quiz actually exists
		assert(file_exists(self::$quizStatFilePath . $result->getQuizName()));
		$content = serialize($result);
		file_put_contents(self::$quizStatFilePath . $result->getQuizName() . '/' . $result->getUserName() . self::$separator . $result->getQuizName() . self::$userStatSuffix, $content);

	}

	/**
	* A function for updating user stats with their newly created quizzes.
	* @param $quiz the newly created Quiz Object.
	* @return null.
	**/

	public function updateUserCreationStats(Quiz $quiz) { // Should be refactored so this code is not duplicated
		// First check for a user stat file (and related directory). If it doesn't exist we'll need to create it
		if (!file_exists(self::$userStatFilePath)) {
    		mkdir(self::$userStatFilePath, 0777, true);
		}

		if (!file_exists(self::$userStatFilePath . $quiz->getCreator() . self::$userStatSuffix)) {
			$user = new LoggedInUser($quiz->getCreator());
			$userStats = new UserStats($user);

			$userStats->addQuiz($quiz);

			$content = serialize($userStats);

			file_put_contents(self::$userStatFilePath . $quiz->getCreator() . self::$userStatSuffix, $content);
		} else {
			// If it already exists, we just need to update the file
			try {
					$fileContent = file_get_contents(self::$userStatFilePath . $quiz->getCreator() . self::$userStatSuffix);
				} catch (Exception $e) {
					// No file found
					throw new Exception("No Such Quiz File Exists!");
				}
				if ($fileContent !== FALSE) {
					$userStats = unserialize($fileContent);
					$userStats->addQuiz($quiz);
					$content = serialize($userStats);

					file_put_contents(self::$userStatFilePath . $quiz->getCreator() . self::$userStatSuffix, $content);
				} else {
					throw new Exception("Something went wrong!");
				}
		}
	}

	/**
	* A function to update a user's stats with results for a quiz they have taken.
	* @param $result the result of a quiz taken.
	* @return null.
	**/

	public function updateUserStats(Result $result) {
		// First check for a user stat file (and related directory). If it doesn't exist we'll need to create it
		if (!file_exists(self::$userStatFilePath)) {
    		mkdir(self::$userStatFilePath, 0777, true);
		}

		if (!file_exists(self::$userStatFilePath . $result->getUserName() . self::$userStatSuffix)) {
			$user = new LoggedInUser($result->getUserName());
			$userStats = new UserStats($user);

			$userStats->addResult($result);

			$content = serialize($userStats);

			file_put_contents(self::$userStatFilePath . $result->getUserName() . self::$userStatSuffix, $content);
		} else {
			// If it already exists, we just need to update the file
			try {
					$fileContent = file_get_contents(self::$userStatFilePath . $result->getUserName() . self::$userStatSuffix);
				} catch (Exception $e) {
					// No file found
					throw new Exception("No Such Quiz File Exists!");
				}
				if ($fileContent !== FALSE) {
					$userStats = unserialize($fileContent);
					$userStats->addResult($result);
					$content = serialize($userStats);

					file_put_contents(self::$userStatFilePath . $result->getUserName() . self::$userStatSuffix, $content);
				} else {
					throw new Exception("Something went wrong!");
				}
		}
	}
}