<?php 
if(1==1)
	{
	$f = setcookie("userguid", "", time());
	$g = setcookie("passweird2", "", time());
	}

require_once("../libraries/initialize.php"); 

	
    $session->logout();
    redirect_to("login.php");
?>
