<?php
	error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_startup_errors', 'On');
ini_set('display_errors', 'On');

require_once('../libraries/initialize.php');
//require_once('../libraries/mobile_detect.php');


if(!$session->is_logged_in()) { redirect_to("login.php"); }

	// 1. the current page number ($current_page)
	$page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;

	// 2. records per page ($per_page)
	$per_page = $session->per_page;
	$per_page = 500;

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
	
	if(isset($_GET['trending']) and $_GET['trending'] != '')
		{
		$session->set_trending($_GET['trending']);
		}
	
	if(isset($_POST['field-find']))
		{
		$session->set_user_search($_POST['field-find']);
		}
		
	//check if a follower is clicked
	if(isset($_GET['follow']) and $_GET['follow'] != 0 and $_GET['follow'] != $_SESSION['user_id'])
		{
		$follower = new User_follows;
		$follower->userguid_follower = $_SESSION['user_id'];
		$follower->userguid_followed = $_GET['follow'];
		$follower->date_followed = date('Y-m-d');
		$follower->date_modified = date('Y-m-d G:i:s');
		$saved = $follower->save();
		
		//now update the follows for each User level
		$sql = "select * from user_levels where userguid = ".$_SESSION['user_id']." limit 1";		
		$follower_level = User_levels::increment_follow($_SESSION['user_id'], 1);
		
		//now update the followed
		$followed_level = User_levels::increment_follow($_GET['follow'], 2);
		}
	if(isset($_GET['stopfollow']) and $_GET['stopfollow'] != 0)
		{
		//$sql = "select * from user_follows where userguid_follower = ".$_SESSION['user_id']." and userguid_followed = ".$_GET['stopfollow']." limit 1";
		//$follower2 = User_follows::find_by_sql($sql);
		$delete = User_follows::delete_follower($_GET['stopfollow']);
		}	
	
	//set the active view for the user's view
	if($session->user_view == 'personal')
		{
		$personal_current = 'class="current"';
		$private_current = '';
		$global_current = '';
		}
	else if($session->user_view == 'private')
		{
		$personal_current = '';
		$private_current = 'class="current"';
		$global_current = '';
		}
	else if($session->user_view == 'global')
		{
		$personal_current = '';
		$private_current = '';
		$global_current = 'class="current"';
		}

	if($session->user_sort == 'Date')
		{
		$sort_date_active = 'class="date active"';
		$sort_alpha_active = 'class="alpha"';
		$sort_relevance_active = 'class="relevance"';
		$sort_new_active = 'class="new"';
		}
	else if($session->user_sort == 'Alphabetical')
		{
		$sort_date_active = 'class="date"';
		$sort_alpha_active = 'class="alpha active"';
		$sort_relevance_active = 'class="relevance"';
		$sort_new_active = 'class="new"';
		}
	else if($session->user_sort == 'Relevance')
		{
		$sort_date_active = 'class="date"';
		$sort_alpha_active = 'class="alpha"';
		$sort_relevance_active = 'class="relevance active"';
		$sort_new_active = 'class="new"';
		}
	else if($session->user_sort == 'Content')
		{
		$sort_date_active = 'class="date"';
		$sort_alpha_active = 'class="alpha"';
		$sort_relevance_active = 'class="relevance"';
		$sort_new_active = 'class="new active"';
		}

	// Need to add ?page=$page to all links we want to 
	// maintain the current page (or store $page in $session)
	
	//get an instance of the logged in user
	$user = User::find_by_id($_SESSION['user_id']);
	$myview = $session->user_view;
	if($session->user_view == 'private')
		$myview = $user->private_cell_name;
	$private_exists = '';
	if(isset($user->private_cell_name))
		$private_exists = $user->private_cell_name;
	$private_edit = '';
	$pvt = Private_User_Topics::return_index($user->private_cell_name);
	$index_part = $pvt[0];
	$admin1_userguid = $pvt[1];
	$admin2_userguid = $pvt[2];
	if($user->userguid == $admin1_userguid or $user->userguid == $admin2_userguid)
		$private_edit = 'Y';

	$jumppads = Jumppad::current_jumppads($per_page, $offset, 0, $index_part);
		
	$global_count = Jumppad::count_jumppads('global',"", 0);
	$personal_count = Jumppad::count_jumppads('personal',"",0);
	$private_count = Jumppad::count_jumppads('private',$user->userguid,$index_part);
	
	//go get an array of Elite Minds (<= 6)
	$limit = 6;
	$find_elite = User_levels::get_elite($limit);
		
	//go get an array of trending content (<= 5)
	$limit = 6;
	$find_trending = Jumppad::get_trends($limit);
	
	//now clear the search field
	$find_knowledge = 'Find Knowledge by Entering a Keyword';
	if($session->user_search != '')
		$session->set_user_search('');
		
		
//load view
$nav_search_discover = 'jumppad.php';
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

$urlnow = '';
$texta  = '';
//$urlnow = 'javascript:(function()%7Bf=\'http://'.$_SERVER['HTTP_HOST'].'/saveit.php?'.html_entity_decode('guid='.$user->userguid.'&url=\'+encodeURIComponent(window.location.href)+\'&title=\'+encodeURIComponent(document.title)+\'&notes=\'+encodeURIComponent(\'\'+(window.getSelection?window.getSelection():document.getSelection?document.getSelection():document.selection.createRange().text))+\'&v=6&\';a=function()%7Bif(!window.open(f+\'noui=1&jump=doclose\',\'soKno6\',\'location=1,links=0,scrollbars=0,toolbar=0\'))location.href=f+\'jump=yes\'%7D;if(/Firefox/.test(navigator.userAgent))%7BsetTimeout(a,0)%7Delse%7Ba()%7D%7D)()');
$texta = '<textarea rows="6" cols="80">javascript:(function()%7Bf=\'http://'.$_SERVER['HTTP_HOST'].'/saveit.php?guid='.$user->userguid.'&url=\'+encodeURIComponent(window.location.href)+\'&title=\'+encodeURIComponent(document.title)+\'&ntes=\'+encodeURIComponent(\'\'+(window.getSelection?window.getSelection():document.getSelection?document.getSelection():document.selection.createRange().text))+\'&v=6&\';a=function()%7Bif(!window.open(f+\'noui=1&jump=doclose\',\'soKno6\',\'location=1,links=0,scrollbars=0,toolbar=0\'))location.href=f+\'jump=yes\'%7D;if(/Firefox/.test(navigator.userAgent))%7BsetTimeout(a,0)%7Delse%7Ba()%7D%7D)()</textarea><br/>';
$in = 1;
if(isset($_GET['in']))
	$in = $_GET['in'];

if($in == 1)
	include('../views/about.php');
else if($in == 2)
	include('../views/guide.php');
else if($in == 3)
	include('../views/tools.php');
else if($in == 4)
	include('../views/team.php');
else if($in == 5)
	include('../views/support.php');
else if($in == 6)
	include('../views/terms.php');
else if($in == 7)
	include('../views/privacy.php');


?>
