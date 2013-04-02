<?php
$myinfo='';   // T H I S   M U S T   B E   S E T   o r   I N C L U D E S   W I L L   F A I L ! ! ! ! 


if (!empty($_POST))
{
	$collect_posted='$_POST=array(';
	foreach($_POST as $key=>$value)
	{
		$collect_posted.="'$key'=>'$value',";
	}
	$collect_posted = substr($collect_posted, 0,-1) . ");";
}
else
{
	$collect_posted="No POST.";
}
//echo $collect_posted;
require_once('../libraries/initialize.php');


if (!$session->is_logged_in()) {
    $notlogged = 'y';
} else {
    $notlogged = '';
}





//load view
$nav_search_discover = 'contact.php';
$currentDiscover='';
$currentPersona='';
$currentSetting=' class="current" ';
echo '
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	
	<title>SO:KNO&trade; | JumpPads</title>
';

include('../views/main_header.php');
include('../views/main_header_div.php');
include('../views/contact.v.php');

//echo "Contact form under construction.";
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
