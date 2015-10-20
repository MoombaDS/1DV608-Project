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
		$this->createView->render();
	}
}