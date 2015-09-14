<?php

//INCLUDE THE FILES NEEDED...
require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');
require_once('controller/LoginController.php');
require_once('model/LoginModel.php');
require_once('model/User.php');
require_once('model/UserDAL.php');

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');

//CREATE OBJECTS OF THE VIEWS
$dal = new UserDAL();
$model = new LoginModel($dal);
$v = new LoginView($model);
$dtv = new DateTimeView();
$lv = new LayoutView();
$controller = new LoginController($v, $lv, $dtv, $model);

$controller->begin();

