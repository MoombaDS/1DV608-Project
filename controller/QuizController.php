<?php

class QuizController {
	private $model;
	private $createView;
	
	public function __construct(QuizModel $model, CreateView $createView) {
		assert(!is_null($model));
		assert(!is_null($createView));
		$this->model = $model;
		$this->createView = $createView;
	}

	public function begin() {
		if ($this->createView->wantsToCreate()) {
			$this->createView->render();

			// Check to see if a valid quiz was created, if so save it
			$completedQuiz = $this->createView->checkForCompletedQuiz();

			if (!is_null($completedQuiz)) {
				$this->model->saveQuiz($completedQuiz);
			}
		}
	}
}