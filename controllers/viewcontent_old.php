<?php
	error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_startup_errors', 'On');
ini_set('display_errors', 'On');

require_once('../libraries/initialize.php');


if(!$session->is_logged_in()) { redirect_to("login.php"); }
	
	if(isset($_GET['curationid']))
		{
		$session->set_curationid($_GET['curationid']);
		}
		
//create the thread
//createthread

	if(isset($_POST['createthread']) and isset($_POST['field-post']) and $_POST['field-post'] != '' and
	   $_POST['field-post'] != 'Share Some Knowledge by Adding a Comment' and
	   isset($_POST['field-title']) and $_POST['field-title'] != '' and 
	   $_POST['field-title'] != 'Enter in the Title for this Thread')
		{
		$validurl = false;
		if(isset($_POST['field-link']) and $_POST['field-link'] != '' and $_POST['field-link'] != 'http://')
			{
			$validurl = true;
			if(filter_var($_POST['field-link'], FILTER_VALIDATE_URL) === false)
				$validurl = false;
			}
			
		$thread = new Comments;
		if($validurl)
			$thread->link = $_POST['field-link'];
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
		$thread->contentid = $session->contentid;
		$thread->curationid = $session->curationid;
			
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

	if(isset($_POST['addtags']))
		{
		//curate the posted tag.
		$curation = new Jumppad;
		$curation->tags = trim($_POST['addtags']);
		$curation->contentid = $session->contentid;
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
	else
		{
		$curation = Jumppad::find_by_id($session->curationid);		
		}

	if(isset($_POST['field-find']))
		{
		$session->set_user_search($_POST['field-find']);
		}
		
	$find_knowledge = 'Find Knowledge by Entering a Keyword';
	if($session->user_search != '')
		$find_knowledge = $session->user_search;

	//use the search class to set filter
	
	if(isset($_GET['contentid']) and $_GET['contentid'] != '')
		{
		$cell = Cell::find_by_id($_GET['contentid']);
		$session->set_contentid($_GET['contentid']);
		$thiscontentid = $_GET['contentid'];
		$thiscurationid = $_GET['curationid'];
		}
	else if(!isset($_GET['contentid']) and isset($_SESSION['contentid']))
		{
		$cell = Cell::find_by_id($_SESSION['contentid']);
		}

	//user_likes_flags is the table that tracks whether the user has liked/unliked the content.
	
	//see if the user has liked this content, set session var: $user_likes_flagsid
	$user_likes_cell = User_likes_flags::content_liked();
	
	if(!$user_likes_cell) //didn't find the user like record
		{
		$likeUnlike = 'like';
		if(isset($_GET['like']))  //now update the like flag
			{
			$user_likes_cell = new User_likes_flags;
			$user_likes_cell->like_unlike  = 1; //like
			$user_likes_cell->curationid   = $session->curationid; 
			$user_likes_cell->contentid    = $session->contentid; 
			$user_likes_cell->topicid      = $session->user_knocell; 
			$user_likes_cell->userid       = $session->user_id; 
			$user_likes_cell->date_created = date('Y-m-d H:i:s');
			$user_likes_cell->save();
				
			$likeUnlike = 'unlike'; //liked now it will say: unlike
			//now increment the likes counter on my curationid
			$curation->increment_likes();
			}
		}
	else
		{
		if($user_likes_cell->like_unlike == 1) //user has liked the cell... toggle to UNLIKE
			{
			$likeUnlike = 'unlike';
			$user_likes_cell->like_unlike  = -1; //unlike
			}
		else
			{
			$likeUnlike = 'like';
			$user_likes_cell->like_unlike  = 1; //like
			}
		if(isset($_GET['like']))
			{
			$user_likes_cell->curationid   = $session->curationid; 
			$user_likes_cell->contentid    = $session->contentid; 
			$user_likes_cell->topicid      = $session->user_knocell; 
			$user_likes_cell->userid       = $session->user_id; 
			$user_likes_cell->date_created = date('Y-m-d H:i:s');
			$user_likes_cell->save();
			if($user_likes_cell->like_unlike == 1)
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
	
//see if the flag has been set, block content if 3 flags set
		$flagUnflag = $curation->flags;
		if(isset($_GET['flag']))  //now update the like flag
			{
			$flagUnflag++; //increment the flag count. don't worry it's the same user for now.
			
			$user_flags_cell = new User_likes_flags;
			$user_flags_cell->like_unlike  = 99; //FLAG = 99
			$user_flags_cell->curationid   = $session->curationid; 
			$user_flags_cell->contentid    = $session->contentid; 
			$user_flags_cell->topicid      = $session->user_knocell; 
			$user_flags_cell->userid       = $session->user_id; 
			$user_flags_cell->date_created = date('Y-m-d H:i:s');
			$user_flags_cell->save();
				
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

	//check if article exists in the Database
	$article = Article::find_by_id($_SESSION['contentid']);
		
	//get an instance of the logged in user
	$user = User::find_by_id($session->user_id);
	$topic = Topic::find_by_id($session->user_knocell);
	$mytopic = $topic->topic;
	$myview = $session->user_view;
	$mycell = $topic->topic;
	$myframe = '';
	$nomedia = false;
	$AE = new AutoEmbed();
	//load the embed source from a remote url
	if (!$AE->parseUrl($cell->url)) {
		// No embeddable video found (or supported)
		$nomedia = true;
	}
	// Get the related image
	$imageURL = $AE->getImageURL();
	$AE->setParam('wmode','transparent');
	$AE->setParam('autoplay','false');	
	$myframe = 	$AE->getEmbedCode();
	
	/*
	$cell->url = str_replace("m.youtube.com/watch?feature=related&","www.youtube.com/watch?",$cell->url);
	$mystr = 'youtube.com';
	$url2 = strtolower($cell->url);
	$pos = strpos($url2, $mystr); //is this a youtube video
	$isiPad = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPad');
	//feature=related& need to remove this text from curations and change the m.youtube.com to www.youtube.com
	$h3tag = true;
	$myimage =	'';
	if($pos === false)
		{
		$posv = strpos($url2, 'vimeo.com'); //vimeo
		if($posv === false)
			{
			//check for godtube
			$posg = strpos($url2, 'godtube.com'); //godtube
			if($posg === false)
				{
				if(isset($article->contentid))
					{
					$myimage = '';
					$posimage = strpos($article->body, '<img');
					if($posimage === false)
						{
						//get the image to display with the article.
						if($image_id = $cell->cell_image($article->contentid)) 					
							$myimage = '<a><img src="retrieveFile.php?id='.$image_id.'" alt="Cell-" ></a>'; 
						}
					
					//need to make the images global... if they exist
					$urlhead = 'http://'.parse_url($cell->url,PHP_URL_HOST);
					$path = parse_url($cell->url,PHP_URL_PATH);
					
					$mybody = str_replace('<a href="/','<a href="'.$urlhead.'/', $article->body);
					$pos = strpos($mybody, ' src="//');
					if($pos === false)
						$mybody = str_replace(' src="/',' src="'.$urlhead.'/', $mybody);
					//if last char of url is a slash / then replace with full url
					if(substr($cell->url,-1) == '/')
						$mybody = str_replace(' src="im',' src="'.$cell->url.'/im', $mybody);
					else
						$mybody = str_replace(' src="im',' src="'.$urlhead.'/im', $mybody);
					if(substr($cell->url,-1) == '/')
						$mybody = str_replace(' src="graph',' src="'.$cell->url.'/graph', $mybody);
					else
						{
						//need to strip off the code and leave the directory...
						
						$mybody = str_replace(' src="graph',' src="'.$urlhead.'/graph', $mybody);
						}
					$mybody = str_replace('<h1','<h3', $mybody);
					$mybody = str_replace('</h1','</h3', $mybody);
					$mybody = str_replace('.com//','.com/', $mybody);
					$mybody = str_replace('.org//','.org/', $mybody);
					$h3tag = false;
					$posh3 = strpos(strtolower($mybody), '<h3');
					if($posh3 !== false)
						$h3tag = true;
					$myframe = $mybody;
					}
				else
					{
					if($isiPad)
						{
						$myframe = '<div style="-webkit-overflow-scrolling:touch; overflow: auto;">
						<iframe  src="'.$cell->url.'" width="100%" scrolling="yes" frameborder="2"></iframe>
						</div>';	
						}
					else
						$myframe = '<iframe  width="760" height="1200" src="'.$cell->url.'" scrolling="yes" frameborder="3"></iframe>';	
					}
				}
			else //godtube
				{
// <script type="text/javascript" src="http://www.godtube.com/embed/source/7ldkypnx.js?w=400&h=255&ap=true&sl=true&title=true"></script><p><a href="http://www.godtube.com/watch/?v=7LDKYPNX">REVELATION SONG - Kari Jobe</a> from <a href="http://www.godtube.com/kari-jobe">kari-jobe</a> on <a href="http://www.godtube.com/">GodTube</a>.</p>
				$modurl = $cell->url;
				$urlhead = 'http://'.parse_url($cell->url, PHP_URL_HOST);
				$urlpath = parse_url($cell->url, PHP_URL_PATH);
				$urlquery = str_replace("v=","", parse_url($cell->url, PHP_URL_QUERY));
				$myframe = '<script type="text/javascript"';
				$myframe .= ' src="'.$urlhead.'/embed/source/'.$urlquery.'.js?w=400&h=255&ap=true&sl=true&title=true"></script><p>';
				//$myframe .= '<a href="'.$cell->url.'">'.$cell->title.'</a> from ';
				//$myframe .= '<a href="http://www.godtube.com/kari-jobe">kari-jobe</a> on <a href="http://www.godtube.com/">GodTube</a>.</p>';
				if(isset($article->contentid))
					{
					$myimage = '';
					$posimage = strpos($article->body, '<img');
					if($posimage === false)
						{
						//get the image to display with the article.
						if($image_id = $cell->cell_image($article->contentid)) 					
							$myimage = '<p><a><img src="retrieveFile.php?id='.$image_id.'" alt="Cell-" ></a><p>'; 
						}
					
					//need to make the images global... if they exist
					$urlhead = 'http://'.parse_url($cell->url,PHP_URL_HOST);
					$mybody = '<p>'.str_replace('<a href="/','<a href="'.$urlhead.'/', $article->body);
					$mybody = str_replace(' src="/',' src="'.$urlhead.'/', $mybody);
					$mybody = str_replace(' src="im',' src="'.$urlhead.'/im', $mybody);
					$mybody = str_replace('<h1','<h3', $mybody);
					$mybody = str_replace('</h1','</h3', $mybody) . "</p>";
					$h3tag = false;
					$posh3 = strpos(strtolower($mybody), '<h3');
					if($posh3 !== false)
						$h3tag = true;
					$myframe .= $mybody;
					}
				}
			}
		else //vimeo
			{
			//<iframe src="http://player.vimeo.com/video/52640864" width="500" height="281" 
			//frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe> 
			//<p><a href="http://vimeo.com/52640864">Hurricane Sandy on Bikes in NYC</a> from 
			//<a href="http://vimeo.com/caseyneistat">Casey Neistat</a> on <a href="http://vimeo.com">Vimeo</a>.</p>
			$urlbuild = str_replace("https://www.","",$cell->url);
			$urlbuild = str_replace("http://www.","",$urlbuild);
			$urlbuild = str_replace("https://","",$urlbuild);
			$urlbuild = str_replace("http://","",$urlbuild);
			$urlbuild = "http://player.".$urlbuild;
			}
		}
	else //youtube embed 
		{
		//need the video link only
		$posvid = strpos($cell->url, "watch?v=") + 8;
		$endurl = strlen($cell->url) - $posvid;
		$posamp = strpos($cell->url, "&");
		$posquestion = strpos($cell->url, "?");
		if($posquestion === false)
			{
			$myframe = '<iframe  width="7600" height="1200" src="'.$cell->url.'" scrolling="yes" frameborder="3"></iframe>';	
			}
		else if($posamp === false)
			{
			$myyoutubeurl = 'http://www.youtube.com/embed/'.substr($cell->url, $posvid, $endurl).'?rel=0';
			$myframe = '<iframe id="ytplayer" type="text/html" width="760" height="480" src="'.$myyoutubeurl.'" frameborder="3" allowfullscreen></iframe>';
			}
		else
			{
			//http://www.youtube.com/watch?v=l6JnowS4774
			$myyoutubeurl = 'http://www.youtube.com/embed/'.str_replace("&","?",substr($cell->url, $posvid, $endurl)).'&rel=0';
			$myframe = '<iframe id="ytplayer" type="text/html" width="760" height="480" src="'.$myyoutubeurl.'" frameborder="3" allowfullscreen></iframe>';
			}
		}
		
		*/
		
		if(isset($article->contentid))
			{
			$myframe .= $article->body;
			}
	//create the userviews record. Save if already exists
	$userview = new Userview;
	$userview->userguid = $user->userguid;
	$userview->contentid = $cell->contentid;
	$userview->topicid = $session->user_knocell;
	$userview->date_added = date('Y-m-d h:i:s');
	$res = $userview->save();
	
	//go get an array of trending content (<= 5)
	$limit = 10;
	$get_tag_trends = Jumppad::get_tag_trends($limit, $session->user_knocell);
	
	$thiscuration = Jumppad::find_by_id($session->curationid);
	
	$cell_discovered_by = Jumppad::get_user_id($cell->contentid);

	$discoverer = User::find_by_id($cell_discovered_by);
	$user_levels = User_levels::find_by_id($discoverer->userguid);
	
	if($user_levels)
		{
		$user_levels->pages_viewed++;
		$ul = $user_levels->save();
		}
	$discoverer_levels = User_levels::find_by_id($discoverer->userguid);
	
	//now update the user_levels for pages viewed 
	$level_array = calculate_level($discoverer, $discoverer_levels);

	$myview = $session->user_view;
	if($session->user_view == 'private')
		$myview = $user->private_cell_name;
	$private_exists = '';
	if(isset($user->private_cell_name))
		$private_exists = $user->private_cell_name;
	$private_edit = '';
	if(isset($user->can_create_private))
		{
		$private_edit = $user->can_create_private;
		$sql = "select * from private_groups_members where userguid = ".$user->userguid;
		//$private_members = $EditPrivate->find_by_sql($sql);
		}

	$jumppadcrumb = $session->user_view;
	if($session->user_view == 'private')
		$jumppadcrumb = $user->private_cell_name;
		
	
	//get the comments
	$thecomments = Comments::find_last_comment($session->contentid, 2);
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
		$sql_topics = "select t.topic, t.topicid from topics as t, private_groups as p where t.privateid > 0 and 
																				 t.privateid = p.privateid 
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
	
//load view
$nav_search_discover = 'cell.php';
$currentDiscover=' class="current" ';
$currentPersona='';
$currentSetting='';
echo '
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	
	<title>SO:KNO&trade; | Content</title>
';
include('../views/main_header.php');
include('../views/main_header_div.php');
include('../views/viewcontent.php');

?>
