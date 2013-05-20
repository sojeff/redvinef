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
	$per_page = 110;

	// 3. total record count ($total_count)
	$total_count = Cell::count_all();
		
	$pagination = new Pagination($page, $per_page, $total_count);
	$offset = $pagination->offset();
	
	if(isset($_POST['createthread']))
		{
		//user is creating a thread... Make sure all required fields are entered.
		if($_POST['field-title'] == '' or $_POST['field-post'] == '')
			{
			alert("Thread title and body are both required! ");
			}
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
	
	if(isset($_POST['createthread']) and isset($_POST['field-post']) and $_POST['field-post'] != '' and
	   $_POST['field-post'] != 'Share Some Knowledge by Adding a Comment' and
	   isset($_POST['field-title']) and $_POST['field-title'] != '' and 
	   $_POST['field-title'] != 'Enter in the Title for this Thread')
		{
		$validurl = false;
		if(isset($_POST['field-link']) and $_POST['field-link'] != '' and $_POST['field-link'] != 'http://')
			{
			
			$fieldlink = str_replace(" ","",$_POST['field-link']);
			$pos = strpos($fieldlink,"http");
			if($pos === false)
				$fieldlink = 'http://'.$fieldlink;
				
			$validurl = true;
			if(filter_var($fieldlink, FILTER_VALIDATE_URL) === false)
				$validurl = false;
			$session->message .= '<br/>link: '.$fieldlink;
			$session->message .= '<br/>pos: '.$pos;
			
			}
			
		$thread = new Comments;
		if($validurl)
			$thread->link = $fieldlink;
		else
			$thread->link = '';
			
		$thread->title = $_POST['field-title'];
		$thread->body = $_POST['field-post'];
		$thread->parentid = 0;
		$curation->curationid = '';	
		$parent_body = $thread->body;
		$parent_link = $thread->link;

		$thread->contentid = 0;
		$thread->curationid = 0;
		$thread->contentid = 0;
		$thread->curationid = 0;
			
		$thread->content_type = 1; //content_type = 2 means sub sub thread
		$thread->userguid = $session->user_id;
		$thread->privateid = 0;
		$thread->comment_date_added = date('Y-m-d H:i:s');
		
		$thread->create();
		$session->set_commentid($thread->commentid);

		//curate the curation related to this comment.
		$curation = new Jumppad;

		$curation->tags = '';
		$curation->contentid = $thread->contentid;
		$curation->userguid  = $session->user_id;
		$curation->topicid   = $session->user_knocell;
		$curation->commentid = $thread->commentid;
		//set to 2 if sub comment
		$curation->content_type = 1;  
		$curation->rank      = 0;
		$curation->views     = 0;
		$curation->likes     = 0;
		$curation->tag_count = 0;
		$curation->comments  = 0;
		$curation->shares    = 0;
		$curation->flags     = 0;
		$curation->date_created = date('Y-m-d H:i:s');
		$curation->date_modified = date('Y-m-d H:i:s');
		
		$curation->create();
		$session->message .= 'curationid: '.$curation->curationid.' - commentid: '.$thread->commentid;
		
		//now update the thread with the newly created curationid.
		$curation->comments  = 1;
		$thread->curationid = $curation->curationid;
		$thread->update();
		
		}
	//get an instance of the logged in user
	$user = User::find_by_id($session->user_id);

	if(isset($_POST['field-find']))
		{
		$session->set_user_search($_POST['field-find']);
		}
	if(isset($_GET['field-find']))
		{
		$session->set_user_search($_GET['field-find']);
		}
	$find_knowledge = 'Find Knowledge by Entering a Keyword';
	if($session->user_search != '')
		$find_knowledge = $session->user_search;

	//use the search class to set filter
	
	if(isset($_GET['user_knocell']))
		{
		$user_knocell = $_GET['user_knocell'];
		$session->set_user_knocell($user_knocell);
		}
	
	//set the active view for the user's view
	if($session->user_view == 'personal')
		{
		$personal_active = 'class="current"';
		$private_active = '';
		$global_active = '';
		//get an array of global topic names 
		$sql_topics = "select t.topic, t.topicid from topics as t, curations as c  
											where t.privateid = 0 and t.topicid = c.topicid and c.userguid = ".$user->userguid."
											group by t.topicid, t.topic 
											order by max(c.curationid) desc limit 42";
		}
	else if($session->user_view == 'private')
		{
		$personal_active = '';
		$private_active = 'class="current"';
		$global_active = '';
		//get an array of global topic names 
		$sql_topics = "select t.topic, t.topicid from topics as t, private_groups as p, private_user_topics as put
								where t.privateid > 0 and t.privateid = p.privateid and put.private_cell_name = '".$user->private_cell_name."' and 
									  put.index_part = p.private_user_index_part
							 order by topic limit 42";
		}
	else if($session->user_view == 'global')
		{
		$personal_active = '';
		$private_active = '';
		$global_active = 'class="current"';
		//get an array of global topic names 
		$sql_topics = "select topic, topicid from topics where global = 'Y' order by topic limit 42";
		}
	$topicarray = Topic::find_by_sql($sql_topics);
	asort($topicarray);
	
	if(isset($_GET['user_sort']) and $_GET['user_sort'] != '')
		{
		$session->set_user_sort($_GET['user_sort']);
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

	if(isset($_GET['knocell_view']) and $_GET['knocell_view'] != '')
		{
		$session->set_knocell_view($_GET['knocell_view']);
		}

	if($session->knocell_view == 'All')
		{
		$knocell_view_all_active = 'class="current"';
		$knocell_view_content_active = '';
		$knocell_view_threads_active = '';
		$knocell_view_qanda_active = '';
		$knocell_view_personas_active = '';
		$myview = 'All';
		}
	else if($session->knocell_view == 'Content')
		{
		$knocell_view_all_active = '';
		$knocell_view_content_active = 'class="current"';
		$knocell_view_threads_active = '';
		$knocell_view_qanda_active = '';
		$knocell_view_personas_active = '';
		$myview = 'Content';
		}
	else if($session->knocell_view == 'Threads')
		{
		$knocell_view_all_active = '';
		$knocell_view_content_active = '';
		$knocell_view_threads_active = 'class="current"';
		$knocell_view_qanda_active = '';
		$knocell_view_personas_active = '';
		$myview = 'Threads';
		}
	else if($session->knocell_view == 'QandA')
		{
		$knocell_view_all_active = '';
		$knocell_view_content_active = '';
		$knocell_view_threads_active = '';
		$knocell_view_qanda_active = 'class="current"';
		$knocell_view_personas_active = '';
		$myview = 'Q&A';
		}
	else if($session->knocell_view == 'People')
		{
		$knocell_view_all_active = '';
		$knocell_view_content_active = '';
		$knocell_view_threads_active = '';
		$knocell_view_qanda_active = '';
		$knocell_view_personas_active = 'class="current"';
		$myview = 'People';
		}
		
	$cells = Jumppad::current_cells($per_page, $offset);

	$jumppadcrumb = $session->user_view;
	if($session->user_view == 'private')
		{
		$jumppadcrumb = $user->private_cell_name;
		$limit = 5;
		if(!isset($user_knocell))
			$user_knocell = 0;
		$find_elite = User_levels::get_elite_private_cell($limit, $user_knocell);
	
		//go get an array of trending content (<= 5)
		$limit = 5;
		$get_tag_trends = Jumppad::get_tag_trends($limit, $session->user_knocell);
		}
	else
		{
		$limit = 5;
		$find_elite = User_levels::get_elite_private_cell($limit);
	
		//go get an array of trending content (<= 5)
		$limit = 5;
		$get_tag_trends = Jumppad::get_tag_trends($limit, $session->user_knocell);
		}

	$private_exists = '';
	if(isset($user->private_cell_name))
		$private_exists = $user->private_cell_name;

	$topic = Topic::find_by_id($session->user_knocell);
	
	$mytopic = $topic->topic;
	if(strlen($mytopic) > 39)
		{
		$mytopic = str_replace("Business", "Bus.", $mytopic);
		$mytopic = str_replace("Environment", "Env.", $mytopic);
		}
	
	//go get an array of Elite Minds (<= 4)
	
	//get the counts for All, Content, People and Threads
	$count_content = Jumppad::count_curations($session->user_knocell, 0);
	$count_threads = Jumppad::count_threads($session->user_knocell);
	$count_people = Jumppad::count_people($session->user_knocell);
	$count_all = $count_content + $count_threads + $count_people;


//load view
$nav_search_discover = 'cell.php';
$nav_search_discover2 = 'cell.php';
$currentDiscover=' class="current" ';
$currentPersona='';
$currentSetting='';
echo '
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	
	<title>SO:KNO&trade; | Knowledge Cells</title>
';
include('../views/main_header.php');
include('../views/main_header_div.php');
include('../views/cell.php');
?>
