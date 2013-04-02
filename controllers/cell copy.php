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
	$per_page = 60;

	// 3. total record count ($total_count)
	$total_count = Cell::count_all();
		
	$pagination = new Pagination($page, $per_page, $total_count);
	$offset = $pagination->offset();

	if(isset($_POST['field-find']))
		{
		$session->set_user_search($_POST['field-find']);
		}
	$find_knowledge = 'Find Knowledge';
	if($session->user_search != '')
		$find_knowledge = $session->user_search;

	//use the search class to set filter
	
	if(isset($_GET['user_knocell']))
		{
		$user_knocell = $_GET['user_knocell'];
		$session->set_user_knocell($user_knocell);
		}
	
	if(isset($_GET['user_sort']) and $_GET['user_sort'] != '')
		{
		$session->set_user_sort($_GET['user_sort']);
		}

	if($session->user_sort == 'Date')
		{
		$sort_date_active = 'class="active"';
		$sort_alpha_active = '';
		$sort_relevance_active = '';
		$sort_content_active = '';
		}
	else if($session->user_sort == 'Alphabetical')
		{
		$sort_date_active = '';
		$sort_alpha_active = 'class="active"';
		$sort_relevance_active = '';
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
		$sort_relevance_active = '';
		$sort_content_active = 'class="active"';
		}
		
	if(isset($_GET['knocell_view']) and $_GET['knocell_view'] != '')
		{
		$session->set_knocell_view($_GET['knocell_view']);
		}

	if($session->knocell_view == 'All')
		{
		$knocell_view_all_active = 'class="active"';
		$knocell_view_content_active = '';
		$knocell_view_threads_active = '';
		$knocell_view_qanda_active = '';
		$knocell_view_personas_active = '';
		$myview = 'All';
		}
	else if($session->knocell_view == 'Content')
		{
		$knocell_view_all_active = '';
		$knocell_view_content_active = 'class="active"';
		$knocell_view_threads_active = '';
		$knocell_view_qanda_active = '';
		$knocell_view_personas_active = '';
		$myview = 'Content';
		}
	else if($session->knocell_view == 'Threads')
		{
		$knocell_view_all_active = '';
		$knocell_view_content_active = '';
		$knocell_view_threads_active = 'class="active"';
		$knocell_view_qanda_active = '';
		$knocell_view_personas_active = '';
		$myview = 'Threads';
		}
	else if($session->knocell_view == 'QandA')
		{
		$knocell_view_all_active = '';
		$knocell_view_content_active = '';
		$knocell_view_threads_active = '';
		$knocell_view_qanda_active = 'class="active"';
		$knocell_view_personas_active = '';
		$myview = 'Q&A';
		}
	else if($session->knocell_view == 'Personas')
		{
		$knocell_view_all_active = '';
		$knocell_view_content_active = '';
		$knocell_view_threads_active = '';
		$knocell_view_qanda_active = '';
		$knocell_view_personas_active = 'class="active"';
		$myview = 'Personas';
		}
	if($myview != 'Personas')
		$cells = Cell::current_cells($per_page, $offset);
	else
		$cells = Cell::persona_cells($per_page, $offset);

	//get an instance of the logged in user
	$user = User::find_by_id($_SESSION['user_id']);
	$topic = Topic::find_by_id($session->user_knocell);
	
	$mytopic = $topic->topic;
	if(strlen($mytopic) > 39)
		{
		$mytopic = str_replace("Business", "Bus.", $mytopic);
		$mytopic = str_replace("Environment", "Env.", $mytopic);
		}
	


//load view
include('../views/cell.php');

?>
