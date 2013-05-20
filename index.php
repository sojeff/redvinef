<?php
	error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_startup_errors', 'On');
ini_set('display_errors', 'On');

require_once('libraries/initialize.php');

$add = '0';
if(isset($_SESSION['user_id']))
	$add = $_SESSION['user_id'];
if(!$session->is_logged_in()) { redirect_to("controllers/login.php"); }

else
	redirect_to("controllers/jumppad.php?userguid=".$add);



?>

