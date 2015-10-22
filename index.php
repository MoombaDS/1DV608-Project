<?php

//INCLUDE THE FILES NEEDED...
require_once('vendor/view/View.php');
require_once('vendor/view/LoginView.php');
require_once('vendor/view/DateTimeView.php');
require_once('vendor/view/LayoutView.php');
require_once('vendor/view/RegisterView.php');
require_once('vendor/controller/LoginController.php');
require_once('vendor/model/LoginModel.php');
require_once('vendor/model/User.php');
require_once('vendor/model/UserDAL.php');
require_once('vendor/model/RegisterModel.php');
require_once('vendor/model/LoggedInUser.php');

require_once('model/Question.php');
require_once('model/Quiz.php');
require_once('model/QuizDAL.php');
require_once('model/QuizStats.php');
require_once('model/UserStats.php');
require_once('model/QuizModel.php');
require_once('model/Result.php');
require_once('model/QuizScorer.php');
require_once('view/CreateView.php');
require_once('view/QuizView.php');
require_once('controller/QuizController.php');

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');

//CREATE OBJECTS OF THE VIEWS
$dal = new UserDAL();
$model = new LoginModel($dal);
$regModel = new RegisterModel($dal);
$v = new LoginView($model);
$rv = new RegisterView($model, $regModel);
$dtv = new DateTimeView();
$lv = new LayoutView();
$controller = new LoginController($v, $rv, $lv, $dtv, $model, $regModel);

$quizDAL = new QuizDAL();
$quizModel = new QuizModel($quizDAL);
$createView = new CreateView($quizModel, $dtv);
$quizView = new QuizView($quizModel, $dtv);
$quizController = new QuizController($quizModel, $createView, $quizView);

if ($model->isLoggedIn()) {
	$quizController->begin();
} else {
	$controller->begin();
}

