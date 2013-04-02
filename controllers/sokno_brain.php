<?php
	error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_startup_errors', 'On');
ini_set('display_errors', 'On');

require_once('../libraries/initialize.php');


if(!$session->is_logged_in()) { redirect_to("login.php"); }

	// 1. the current page number ($current_page)
	$page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;

	// 2. records per page ($per_page)
	$per_page = $session->per_page;

	// 3. total record count ($total_count)
	$total_count = Jumppad::count_all();
		
	$pagination = new Pagination($page, $session->per_page, $total_count);

	//use the search class to set filter
	$offset = $pagination->offset();
	
	if(isset($_GET['user_view']) and $_GET['user_view'] != '')
		{
		$session->set_user_view($_GET['user_view']);
		}
	
	if(isset($_GET['user_sort']) and $_GET['user_sort'] != '')
		{
		$session->set_user_sort($_GET['user_sort']);
		}
	
	if(isset($_POST['field-find']))
		{
		$session->set_user_search($_POST['field-find']);
		}
	//set the active view for the user's view
	if($session->user_view == 'personal')
		{
		$personal_active = 'class="active"';
		$private_active = '';
		$global_active = '';
		$curr_view = 'personal';
		}
	else if($session->user_view == 'private')
		{
		$personal_active = '';
		$private_active = 'class="active"';
		$global_active = '';
		$curr_view = 'private';
		}
	else if($session->user_view == 'global')
		{
		$personal_active = '';
		$private_active = '';
		$global_active = 'class="active"';
		$curr_view = 'global';
		}

	if($session->user_sort == 'Date')
		{
		$sort_date_active = 'class="active"';
		$sort_alpha_active = '';
		$sort_comm_active = '';
		$sort_content_active = '';
		}
	else if($session->user_sort == 'Alphabetical')
		{
		$sort_date_active = '';
		$sort_alpha_active = 'class="active"';
		$sort_comm_active = '';
		$sort_content_active = '';
		}
	else if($session->user_sort == 'Relevance')
		{
		$sort_date_active = '';
		$sort_alpha_active = '';
		$sort_relevance_active = 'class="active"';
		$sort_content_active = '';
		}
	else if($session->user_sort == 'Content')
		{
		$sort_date_active = '';
		$sort_alpha_active = '';
		$sort_comm_active = '';
		$sort_content_active = 'class="active"';
		}

	$find_knowledge = 'Find Knowledge';
	if($session->user_search != '')
		$find_knowledge = $session->user_search;
		
	//get an instance of the logged in user
	if(isset($_GET['user_id']) and $_GET['user_id'] != 0)
		$user = User::find_by_id($_GET['user_id']);
	else
		$user = User::find_by_id($_SESSION['user_id']);
	$myview = $user->name;
	
	$session->set_user_view('personal');		
	$jumppads = Jumppad::current_jumppads($per_page, $offset, $user->userguid); //get the personal jumppads for the specified user

	$avatar = "../theme/img/ui/icon-avatar.png";
	if(file_exists("img/avatar_70x70/".$user->userguid.".jpg"))
		$avatar = "img/avatar_70x70/".$user->userguid.".jpg";
//load view
$nav_search_discover = 'jumppad.php';

echo '
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	
	<title>SO:KNO&trade; | JumpPads</title>
';

include('../views/main_header.php');
include('../views/main_header_div.php');
include('../views/sokno_brain.php');

//return to current session setting...	
	$session->set_user_view($curr_view);	

?>
