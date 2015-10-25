<?php

/**
* Controller class which handles passing any information to the model to be saved and deciding which view needs to be rendered.
*
**/

class QuizController {
	private $model;
	private $createView;
	private $quizView;
	private $userView;
	private $quizListView;
	
	public function __construct(QuizModel $model, CreateView $createView, QuizView $quizView, UserView $userView, QuizListView $quizListView) {
		assert(!is_null($model));
		assert(!is_null($createView));
		assert(!is_null($quizView));
		assert(!is_null($userView));
		assert(!is_null($quizListView));
		$this->model = $model;
		$this->createView = $createView;
		$this->quizView = $quizView;
		$this->userView = $userView;
		$this->quizListView = $quizListView;
	}

	/**
	* Core function of the controller. Decides which view to render by asking the views in question.
	* @return null
	**/

	public function begin() {
		if ($this->createView->wantsToCreate()) {
			$this->createView->render();

			// Check to see if a valid quiz was created, if so save it
			$completedQuiz = $this->createView->checkForCompletedQuiz();

			if (!is_null($completedQuiz)) {
				$this->model->saveQuiz($completedQuiz);
			}
		} else if ($this->quizView->submittedQuiz()) { // If the user has submitted answers for a quiz
			$scorer = new QuizScorer();
			$answers = $this->quizView->getAnswers();
			$result = $scorer->scoreQuiz($this->model->getQuiz($this->quizView->requestingQuiz()), $this->model->getLoggedInUser(), $answers);
			// Send result to the view and save it in model

			// Only save if this is not a resubmission of the POST data (i.e. if the test has already been completed)
			if (!$this->model->hasUserTakenQuiz($this->quizView->requestingQuiz(), $this->model->getLoggedInUser())) {
				// Save results for both user and quiz
				$this->model->updateQuizStats($result);
			}

			$this->quizView->renderScore($result);

		} else if ($this->quizView->startedQuiz()) { // If the user has elected to start a quiz
			$this->quizView->renderQuizQuestions($this->quizView->requestingQuiz());
		} else if ($this->quizView->requestingQuiz() != null) { // If the user is requesting to view the stat page for a quiz
			$this->quizView->render($this->quizView->requestingQuiz());
		} else if ($this->userView->requestingUser() != null) { // If the user is requesting to view a user page
			$this->userView->render($this->userView->requestingUser());
		} else if ($this->quizListView->wantsList()) { // If the user is requesting the full quiz list
			$this->quizListView->render();
		} else { // Otherwise we just show the landing page
			$this->quizListView->renderLanding();
		}
	}
}