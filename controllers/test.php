<?php

require_once('Smarty.class.php');
require_once('../models/test.php');

$view = new Smarty();
$view->template_dir = '../views';
$view->compile_dir = '../tmp';

$model = new testModel();
$model->loadData("this is my query or args");

$view->assign('myHeading1', 	$model->var1 );
$view->assign('myParagraph', 	$model->var2 );
$view->assign('myHeading2', 	$model->var3 );
$view->assign('myCommunities', 	$model->var4 );

$view->display('test.tpl');
