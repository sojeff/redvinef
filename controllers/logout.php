<?php require_once("../libraries/initialize.php"); ?>
<?php	
    $session->logout();
    redirect_to("login.php");
?>
