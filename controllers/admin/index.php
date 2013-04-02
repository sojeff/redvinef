<?php
	error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_startup_errors', 'On');
ini_set('display_errors', 'On');

require_once('../../libraries/initialize.php');


if(!$session->is_logged_in()) { redirect_to("login.php"); }


//    views
?>

<?php include_layout_template('admin_header.php'); ?>

<?php include_layout_template('admin_main_menu.php'); ?>

<?php include_layout_template('admin_footer.php'); ?>
