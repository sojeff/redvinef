<?php
	error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_startup_errors', 'On');
ini_set('display_errors', 'On');

require_once('../libraries/initialize.php');


if(!$session->is_logged_in()) { redirect_to("login.php"); }
	
	$create_thread = false;
	if(isset($_GET['create']) and $_GET['create'] == 'yes')
		{
		$create_thread = true;
		$first_thread = '';
		$first_get = '&first=yes';
		$parentid = 0;
		}
	
	if(isset($_GET['curationid']))
		{
		$session->set_curationid($_GET['curationid']);
		}
	
	if(isset($_GET['commentid']) and $_GET['commentid'] != 0)
		{
		$thecomments = Comments::find_by_id($_GET['commentid']);
		if($thecomments->parentid == 0)
			$session->set_commentid($_GET['commentid']);
		else //find the parent id...
			{
			$thecomments = Comments::find_by_id($thecomments->parentid);
			$session->set_commentid($thecomments->commentid);
			}
		}
/*	if(isset($session->commentid) and $session->commentid != 0 )
		{
		$thecomments = Comments::find_by_id($session->commentid);
		$session->message .= "<br/>inside...<br/>";
		}
*/
	if(isset($_GET['came_from_knocel']) and $_GET['came_from_knocel'] == 'Y')
		{
		$came_from_knocel = true;
		}
	else
		{
		$came_from_knocel = false;
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
	

	if(isset($_POST['field-tag']))
		{
		//curate the posted tag.
		$curation = new Jumppad;
		$curation->tags = trim($_POST['field-tag']);
		$curation->contentid = trim($_POST['field-contentid']);
		$curation->userguid  = $session->user_id;
		$curation->topicid   = $session->user_knocell;
		$curation->rank      = 0;
		$curation->views     = 1;
		$curation->likes     = 0;
		$curation->tag_count = 1;
		$curation->comments  = 0;
		$curation->shares    = 0;
		$curation->flags     = 0;
		$curation->date_created = date('Y-m-d H:i:s');
		
		$curation->save();
		$session->set_curationid($curation->curationid);
		}
	else if(isset($session->curationid))
		{
		$curation = Jumppad::find_by_id($session->curationid);		
		}

	if(isset($_POST['field-find']))
		{
		$session->set_user_search($_POST['field-find']);
		}
		
	$find_knowledge = 'Find Knowledge';
	if($session->user_search != '')
		$find_knowledge = $session->user_search;

	//use the search class to set filter
	$cell_selected = false;
	if(isset($_GET['contentid']) and $_GET['contentid'] != '' and $_GET['contentid'] != 0)
		{
		$cell = Cell::find_by_id($_GET['contentid']);
		$session->set_contentid($_GET['contentid']);
		$thiscontentid = $_GET['contentid'];
		$thiscurationid = $_GET['curationid'];
		$cell_selected = true;
		}
	else if(isset($_GET['contentid']) and $_GET['contentid'] == 0)
		{
		$_SESSION['contentid'] = 0;
		$session->set_contentid(0);
		}
	else if(!isset($_GET['contentid']) and 
	         isset($_SESSION['contentid']) and $_SESSION['contentid'] != 0)
		{
		$cell = Cell::find_by_id($_SESSION['contentid']);
		$cell_selected = true;
		}
	//if i came stright from cell.php I need to add a non-comment thread, or update a thread that was clicked on.
	
	//**************
	
	//get the parent comment id: parentid = 0
	$parent_commentid = 0;
	if($cell_selected)
		{
		$sql = "select * from comments where contentid = ".$session->contentid." and parentid = 0";
		$parentcomment = Comments::find_by_sql($sql);
		if($parentcomment)
			{
			foreach($parentcomment as $parentcomm)
				{
				$parent_commentid = $parentcomm->commentid;
				$parent_title = $parentcomm->title;
				if($parent_title == '')
					$parent_title = $cell->title;
				$parent_body = $parentcomm->body;
				$parent_link = $parentcomm->link;
				}
			}
		}
	else if(isset($session->commentid) and $session->commentid != 0)
		{
		$parent_commentid = $session->commentid;
		}
	
	$validurl = false;
	if(isset($_POST['field-link']))
		{
		$validurl = true;
		if(filter_var($_POST['field-link'], FILTER_VALIDATE_URL) === false)
			$validurl = false;
		}
			
	if(isset($_POST['button-addcomment']) and 
		((isset($_POST['field-comment']) and $_POST['field-comment'] != '' and $_POST['field-comment'] != 'Share Some Knowledge') or 
	 	(isset($_POST['field-link']) and $_POST['field-link'] != '' and $_POST['field-link'] != 'Enter Link')))
		{
		//create a new thread
		//make sure link is a good link!!!

		$create_thread = false;
		if($session->commentid == 0)
			$first_thread = $_POST['field-comment'];
		$first_get = '&first=no';
		
		$thread = new Comments;
		if($validurl)
			$thread->link = $_POST['field-link'];
		else
			$thread->link = '';
			
		if($_POST['field-comment'] != '' and $_POST['field-comment'] != 'Enter Comment')
			$thread->body = $_POST['field-comment'];
		else
			$thread->body = '';

		if($parent_commentid != 0)
			{
			//create a new sub thread pointing to parent thread, not a sub sub thread
			$thread->commentid = '';
			$thread->parentid = $parent_commentid;
			//i need to create a new curation record for this user's new sub thread
			$curation->curationid = '';			
			}
		else  //creating a new thread 
			{
			$thread->commentid = '';
			$thread->parentid = 0;
			$curation->curationid = '';	
			$parent_title = $thread->title;
			$parent_body = $thread->body;
			$parent_link = $thread->link;
			}
			
		if(isset($_POST['parentid']) and $_POST['parentid'] != 0)
			$thread->parentid = $_POST['parentid'];
			
		$thread->contentid = 0;
		$thread->curationid = 0;
		if($cell_selected)
			{
			$thread->contentid = $cell->contentid;
			//$thread->curationid = $session->curationid;
			}
			
		$thread->content_type = 1; //content_type = 2 means sub sub thread
		$thread->userguid = $session->user_id;
		$thread->privateid = 0;
		$thread->comment_date_added = date('Y-m-d H:i:s');
		
		$thread->create();
		if(isset($_POST['parentid']) and $_POST['parentid'] != 0)
			$session->set_commentid($thread->parentid);
		else
			$session->set_commentid($thread->commentid);

		//curate the curation related to this comment.
		$curation = new Jumppad;
		//if(isset($session->curationid))
			//$curation->curationid = $session->curationid;
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
		$curation->comments  = 1;
		$curation->shares    = 0;
		$curation->flags     = 0;
		$curation->date_created = date('Y-m-d H:i:s');
		$curation->date_modified = date('Y-m-d H:i:s');
		
		$curation->create();
		$session->set_curationid($curation->curationid);
		$session->message .= 'curationid: '.$curation->curationid.' - commentid: '.$thread->commentid;
		
		//now update the thread with the newly created curationid.
		$thread->curationid = $curation->curationid;
		$thread->update();

		}
	
	//user_likes_flags is the table that tracks whether the user has liked/unliked the content.
	
	//see if the user has liked this comment, set session var: $user_likes_flagsid
	if(isset($_GET['likecommentid']) and isset($_GET['like']) and $_GET['likecommentid'] != 0)
		{
		$user_likes = User_likes_flags::comment_liked($_GET['likecommentid']);
		
		if(!$user_likes) //didn't find the user like record
			{
			$likeUnlike = 'like';
			$user_likes = new User_likes_flags;
			$user_likes->like_unlike  = 1; //like
			$user_likes->curationid   = $session->curationid; 
			$user_likes->commentid    = $_GET['likecommentid']; 
			$user_likes->topicid      = $session->user_knocell; 
			$user_likes->userid       = $session->user_id; 
			$user_likes->date_created = date('Y-m-d H:i:s');
			$user_likes->save();
				
			$likeUnlike = 'unlike'; //liked now it will say: unlike
			//now increment the likes counter on my curationid
			$curation->increment_likes();
			}
		else
			{
			if($user_likes->like_unlike == 1) //user has liked the cell... toggle to UNLIKE
				{
				$likeUnlike = 'unlike';
				$user_likes->like_unlike  = -1; //unlike
				}
			else
				{
				$likeUnlike = 'like';
				$user_likes->like_unlike  = 1; //like
				}
			if(isset($_GET['like']))
				{
				$user_likes->curationid   = $session->curationid; 
				$user_likes->commentid    = $_GET['likecommentid']; 
				$user_likes->topicid      = $session->user_knocell; 
				$user_likes->userid       = $session->user_id; 
				$user_likes->date_created = date('Y-m-d H:i:s');
				$user_likes->save();
				if($user_likes->like_unlike == 1)
					{
					//now increment the likes counter on my curationid
					$curation->increment_likes();
					$likeUnlike = 'unlike';
					}
				else
					{
					//now increment the likes counter on my curationid
					$curation->decrement_likes();
					$likeUnlike = 'like';
					}
				
				}
			}
		}
		
//see if the flag has been set, block comment if 3 flags set
	$flagUnflag = $curation->flags;
	if(isset($_GET['flag']))  //now update the like flag
		{
		$flagUnflag++; //increment the flag count. don't worry it's the same user for now.
		
		$user_flags = new User_likes_flags;
		$user_flags->like_unlike  = 99; //FLAG = 99
		$user_flags->curationid   = $session->curationid; 
		$user_flags->commentid    = $_GET['flagcommentid']; 
		$user_flags->topicid      = $session->user_knocell; 
		$user_flags->userid       = $session->user_id; 
		$user_flags->date_created = date('Y-m-d H:i:s');
		$user_flags->save();
			
		//now increment the likes counter on my curationid
		$curation->increment_flags();
		}

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
		$delete = User_follows::delete_follower($_GET['stopfollow']);
		}	
		
	//get an instance of the logged in user
	$user = User::find_by_id($session->user_id);
	$topic = Topic::find_by_id($session->user_knocell);
	$myview = $session->user_view;
	$mycell = $topic->topic;

	$isiPad = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPad');
	
	//go get an array of trending content (<= 5)
	$limit = 10;
	$get_tag_trends = Jumppad::get_tag_trends($limit, $session->user_knocell);
	$thiscuration = Jumppad::find_by_id($session->curationid);
	
	//get the latest comment info.
	$last_comment = '';
	unset($last_comment_date_added);
//	unset($countcomments);
//	unset($discoverer);
//	unset($discoverer_levels);
//	unset($level_array);

	//$parent_link = 0;
	if(isset($thecomments))
	{
	if($thecomments->title != '')
		$parent_title = $thecomments->title;
	$parent_body  = $thecomments->body;
	
	$countcommentlikes = 0;
	$countcommentfollows = 0;

	 $lastcomm = $thecomments->find_last_comment($session->commentid, 1);
	 if($lastcomm)
	 	{
	 	foreach($lastcomm as $lastcomment)
	 		{
	 		$last_comment_date_added = $lastcomment->comment_date_added;
	 		}
		$countcommentlikes = Comments::count_comment_likes($lastcomment->commentid);
		//$countcommentfollows = Comments::count_comment_follows($lastcomment->commentid);

		}
	
	$countcomments = Comments::count_comments($session->commentid);
	
	//get the related content (if any)
	$related_content = Comments::related_content($session->commentid);
	
	$discoverer = User::find_by_id($thecomments->userguid);
	$discoverer_levels = User_levels::find_by_id($discoverer->userguid);
	$level_array = calculate_level($discoverer, $discoverer_levels);
	
	if(isset($parent_commentid) and $parent_commentid != 0)
		{
		$sql = "select * from comments where parentid = '$parent_commentid' order by commentid asc ";
		$thesubcomments = Comments::find_by_sql($sql);
		}
	else
		{
		$sql = "select * from comments where parentid = ".$session->commentid." order by commentid asc ";
		$thesubcomments = Comments::find_by_sql($sql);
		$session->message .= "<br/>last sql: ".$sql;
		}
	}
		$private_exists = '';
	if(isset($user->private_cell_name))
		$private_exists = $user->private_cell_name;

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

	
$nav_search_discover = 'cell.php';
	
echo '
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	
	<title>SO:KNO&trade; | Threads</title>
';
include('../views/main_header.php');
include('../views/main_header_div.php');
include('../views/thread.php');

?>
