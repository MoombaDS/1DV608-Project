<?php

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

	public function begin() {
		if ($this->createView->wantsToCreate()) {
			$this->createView->render();

			// Check to see if a valid quiz was created, if so save it
			$completedQuiz = $this->createView->checkForCompletedQuiz();

			if (!is_null($completedQuiz)) {
				$this->model->saveQuiz($completedQuiz);
			}
		} else if ($this->quizView->submittedQuiz()) {
			$scorer = new QuizScorer();
			$answers = $this->quizView->getAnswers();
			$result = $scorer->scoreQuiz($this->model->getQuiz($this->quizView->requestingQuiz()), $this->model->getLoggedInUser(), $answers);
			// Send result to the view and save it in model

			// Only save if this is not a resubmission of the POST data (i.e. if the test has already been completed)
			if (!$this->model->hasUserTakenQuiz($this->quizView->requestingQuiz(), $this->model->getLoggedInUser())) {
				// Save results for both user and quiz
				$this->model->updateQuizStats($result);
				// TODO save results for the user
			}

			$this->quizView->renderScore($result);

		} else if ($this->quizView->startedQuiz()) {
			$this->quizView->renderQuizQuestions($this->quizView->requestingQuiz());
		} else if ($this->quizView->requestingQuiz() != null) {
			$this->quizView->render($this->quizView->requestingQuiz());
		} else if ($this->userView->requestingUser() != null) {
			$this->userView->render($this->userView->requestingUser());
		} else if ($this->quizListView->wantsList()) {
			$this->quizListView->render();
		} else {
			$this->quizListView->renderLanding();
		}
	}
}