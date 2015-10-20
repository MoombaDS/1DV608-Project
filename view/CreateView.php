<?php

class CreateView {
	private $quizModel;
	private $dateTimeView;
	
	public function __construct(QuizModel $quizModel, DateTimeView $dateTimeView) {
		assert(!is_null($quizModel));
		assert(!is_null($dateTimeView));
		$this->quizModel = $quizModel;
		$this->dateTimeView = $dateTimeView;
	}

	public function render() {
	    echo '<!DOCTYPE html>
	      <html>
	        <head>
	          <meta charset="utf-8">
	          <title>Create New Quiz</title>
	        </head>
	        <body>
	          <h1>Create New Quiz</h1>
	          	' . $this->displayForm() . '
	          <div class="container">
	              ' . $this->dateTimeView->show() . '
	          </div>
	         </body>
	      </html>
	    ';
	}

	public function displayForm() {
		// Check POST and validate input
	}

}