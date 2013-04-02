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
	$per_page = 90;

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
		$sort_date_active = 'class="date active"';
		$sort_alpha_active = 'class="alpha"';
		$sort_comm_active = 'class="comm"';
		$sort_relevance_active = 'class="relevance"';
		$sort_content_active = 'class="content"';
		}
	else if($session->user_sort == 'Alphabetical')
		{
		$sort_date_active = 'class="date"';
		$sort_alpha_active = 'class="alpha active"';
		$sort_comm_active = 'class="comm"';
		$sort_relevance_active = 'class="relevance"';
		$sort_content_active = 'class="content"';
		}
	else if($session->user_sort == 'Relevance')
		{
		$sort_date_active = 'class="date"';
		$sort_alpha_active = 'class="alpha"';
		$sort_comm_active = 'class="comm"';
		$sort_relevance_active = 'class="relevance active"';
		$sort_content_active = 'class="content"';
		}
	else if($session->user_sort == 'Content')
		{
		$sort_date_active = 'class="date"';
		$sort_alpha_active = 'class="active"';
		$sort_comm_active = 'class="comm"';
		$sort_relevance_active = 'class="relevance"';
		$sort_content_active = 'class="content active"';
		}

	$find_knowledge = 'Find Knowledge by Entering a Keyword';
	if($session->user_search != '')
		$find_knowledge = $session->user_search;
		
	//get an instance of the logged in user
	$user = User::find_by_id($_SESSION['user_id']);
	
	if(isset($_GET['user_id']) and $_GET['user_id'] != 0)
		{
		//different persona than User
		$persona = User::find_by_id($_GET['user_id']);
		if(isset($_GET['follow']) and $_GET['follow'] == 'stop')
			{
			$delete = User_follows::delete_follower($_GET['user_id']);
			}
		else if(isset($_GET['follow']) and $_GET['follow'] == 'start')
			{
			$follower = new User_follows;
			$follower->userguid_follower = $_SESSION['user_id'];
			$follower->userguid_followed = $_GET['user_id'];
			$follower->date_followed = date('Y-m-d');
			$follower->date_modified = date('Y-m-d G:i:s');
			$saved = $follower->save();
			
			//now update the follows for each User level
			$sql = "select * from user_levels where userguid = ".$_SESSION['user_id']." limit 1";		
			$follower_level = User_levels::increment_follow($_SESSION['user_id'], 1);
			
			//now update the followed
			$followed_level = User_levels::increment_follow($_GET['user_id'], 2);
			}
		//is the user following this selected persona?
		//$following_thisperson = false;
		//$following_thisperson = User_follows::get_followers($persona->userguid);
		}
	else
		$persona = $user;

	$myview = $persona->name;
	
	$session->set_user_view('personal');		
	$cells = Jumppad::current_user_cells($per_page, $offset, $persona->userguid); //get the personal jumppads for the specified user
	$find_level = User_levels::find_by_id($persona->userguid);
	$level_array = calculate_level($persona, $find_level);
	
	//calculate the time this person has been a so:kno user
	$time = diff_times($persona->updated_time); 
	if(substr($time,0,2) != '1 ')
		$add = 's';
	else
		$add = '';
	
	$cell = Jumppad::current_cell($persona->userguid);
	//now prep the current curation for content or thread

	$not_in_my_thread_array = true;
	$commenttitle = "Blank Title";
	$maincurationid = 0;  //set up variables for hyperlinks
	$maincommentid  = 0;
	//get the full curation record...
/*
echo '<pre>';
print_r($cell);
echo '</pre>';
*/
	if(isset($cell->content_type) and ($cell->content_type == 1 or $cell->content_type == 2) and $cell->commentid != 0) //comment
		{
		$comment = Comments::find_by_id($cell->commentid);
		
		$commentbody = $comment->body;
		if(strlen($comment->body) < 35)
			$commentbody = $commentbody . '<br><br><br>';
		else if(strlen($comment->body) < 60)
			$commentbody = $commentbody . '<br><br>';
		else if(strlen($comment->body) < 90)
			$commentbody = $commentbody . '<br>';
		else if(strlen($comment->body) > 120)
			{
			$commentbody = substr($comment->body,0,160)."...";
			}
		
		$time2 = diff_times($comment->comment_date_added); 
		if(substr($time2,0,2) != '1 ')
			$add2 = 's';
		else
			$add2 = '';
		$time3 = $time2;
		$add3 = $add2;
		if(isset($parentparent))
			$time3 = diff_times($parentparent->comment_date_added); 
		else if(isset($parentcomment))
			$time3 = diff_times($parentcomment->comment_date_added); 
		else 
			$time3 = diff_times($comment->comment_date_added); 
			
		if(substr($time3,0,2) != '1 ')
			$add3 = 's';
		else
			$add3 = '';
		
		$maincurationid = $comment->curationid;  //set up variables for hyperlinks
		$maincommentid  = $comment->commentid;
		if($comment->title != '')
			{
			if(strlen($comment->title) > 50)
				$commenttitle = substr($comment->title,0,50).'...'; //truncate the title on this view
			else
				$commenttitle = $comment->title;
			}
		if($comment->parentid != 0) //need parent thread
			{
			$parentcomment = Comments::find_by_id($comment->parentid);
			if($parentcomment->title != '')
				{
				if(strlen($parentcomment->title) > 50)
					$commenttitle = substr($parentcomment->title,0,50).'...';
				else
					$commenttitle = $parentcomment->title;
				}
			$maincurationid = $parentcomment->curationid;  //set up variables for hyperlinks
			$maincommentid  = $parentcomment->commentid;
			if($cell->content_type == 2 and $parentcomment->parentid != 0) //need to get the parent/parent thread of sub comment
				{
				$parentparent = Comments::find_by_id($parentcomment->parentid);
				if($parentparent->title != '')
					if(strlen($parentparent->title) > 50)
						$commenttitle = substr($parentparent->title,0,50).'...';
					else
						$parentparent = $parentcomment->title;
				$maincurationid = $parentparent->curationid;  //set up variables for hyperlinks
				$maincommentid  = $parentparent->commentid;
				}
			}
		
		}

	if(isset($cell->content_type) and $cell->content_type == 0 and $cell->contentid != 0) //content
		{
		
		$content = Cell::find_by_id($cell->contentid); 
		$celltitle = substr(str_replace("www.","",parse_url($content->url,PHP_URL_HOST)),0,28);
		$celltitle = str_replace("m.youtube.com","youtube.com",$celltitle);
		$cellhref = 'viewcontent.php?contentid='.$cell->contentid.'&curationid='.$cell->curationid.'&userguid='.$cell->userguid;
		$cellimagesize = $content->cell_image_size($cell->contentid);
		
		if($cellimagesize != '' and $image_id = $content->cell_image($cell->contentid)) 
			{
			$width = $content->cell_image_size($cell->contentid);
			$width = str_replace('width="','',$width);
			$length = $width;
			$width = strtok($width,'"');
			$length = strtok($length, 'height="');
			$length = strtok('"');
			$length = strtok('"');
			if($length > 0 and $width > 0)
				{
				$ratio = $length/$width;
				if($length >= $width or $ratio > .72)
					{
					$x2 = round(128 * $width / $length);
					if($x2 > 228)
						$x2 = 228;
					$image_display = '<img src="retrieveFile.php?id='.$image_id.'" alt="" width="'.$x2.'" height="128">'; 
					}
				else
					{
					$y2 = round(228 * $length / $width);
					if($y2 > 128)
						$y2 = 128;
					$image_display = '<img src="retrieveFile.php?id='.$image_id.'" alt="" width="228" height="'.$y2.'">'; 
					}
				}
			else
				{
				$image_display = '<img src="retrieveFile.php?id='.$image_id.'" alt="" width="228" height="128">'; 
				}
			}
		else
			$image_display = '<img src="../theme/img/ui/placeholder-thumb.png" alt="" width="228" height="128">'; 
		
		if(strlen($content->title) >= 62)
			$body = substr($content->title,0,59) . "..."; 
		else
			$body = $content->title;
		
		//now do the stats for this curationid
		$time3 = diff_times($content->date_created); 
		if(substr($time,0,2) != '1 ')
			$add3 = 's';
		else
			$add3 = '';
		
		}
		
	//check if a follower is clicked
	if(isset($_GET['followstartstop']) and $_GET['followstartstop'] == 'start' and 
	   isset($_GET['user_follow']) and $_GET['user_follow'] != 0 and 
	   ((isset($_GET['user_id']) and $_GET['user_id'] == $session->user_id) or isset($_GET['specialflip']) and $_GET['specialflip'] == 'Y'))
		{
		$follower = new User_follows;
		if(isset($_GET['specialflip']) and $_GET['specialflip'] == 'Y') //flip to keep view the same...
			{
			$follower->userguid_follower = $_GET['user_follow'];
			$follower->userguid_followed = $_GET['user_id'];
			}
		else
			{
			$follower->userguid_follower = $_GET['user_id'];
			$follower->userguid_followed = $_GET['user_follow'];
			}
		$follower->date_followed = date('Y-m-d');
		$follower->date_modified = date('Y-m-d G:i:s');
		$saved = $follower->save();
		
		//now update the follows for each User level
		$sql = "select * from user_levels where userguid = ".$_SESSION['user_id']." limit 1";		
		$follower_level = User_levels::increment_follow($_SESSION['user_id'], 1);
		
		//now update the followed
		$followed_level = User_levels::increment_follow($_GET['user_follow'], 2);
		}
	if(isset($_GET['followstartstop']) and $_GET['followstartstop'] == 'stop' and ($_GET['user_id'] == $session->user_id or isset($_GET['specialflip']) and $_GET['specialflip'] == 'Y'))
		{
		//$sql = "select * from user_follows where userguid_follower = ".$_SESSION['user_id']." and userguid_followed = ".$_GET['stopfollow']." limit 1";
		//$follower2 = User_follows::find_by_sql($sql);
		if($_GET['specialflip'] == 'Y')
			$delete = User_follows::delete_follower($_GET['user_id']);
		else
			$delete = User_follows::delete_follower($_GET['user_follow']);
		}	
	
	$find_level = User_levels::find_by_id($persona->userguid);
	$level_array = calculate_level($persona, $find_level);
	$connections = User_follows::count_followers($persona->userguid, 1) + User_follows::count_followers($persona->userguid, 2);
	$following_thisperson = false;
	$following_thisperson = User_follows::get_followers($persona->userguid);
	$followers_filter = 'followers';
	$followers = '<li><a class="followme active" href="#">Followers</a></li>';
	$following = '<li><a class="followthem" href="persona.php?in=3&follower=following&user_id='.$persona->userguid.'">Following</a></li>';
	$followers_array = User_follows::get_all_followers($persona->userguid, "followers");
	if(isset($_GET['follower']) and $_GET['follower'] == 'followers')
		{
		$followers_filter = 'followers';
		$followers = '<li><a class="followme active" href="#">Followers</a></li>';
		$following = '<li><a class="followthem" href="persona.php?in=3&follower=following&user_id='.$persona->userguid.'">Following</a></li>';
		$followers_array = User_follows::get_all_followers($persona->userguid, "followers");
		}
	if(isset($_GET['follower']) and $_GET['follower'] == 'following')
		{
		$followers_filter = 'following';
		$followers = '<li><a class="followme" href="persona.php?in=3&follower=followers&user_id='.$persona->userguid.'">Followers</a></li>';
		$following = '<li><a class="followthem active" href="#">Following</a></li>';
		$followers_array = User_follows::get_all_followers($persona->userguid, "following");
		}

	$total_contributions = Jumppad::count_jumppads_topic('personal', $persona->userguid, 0, "");
	$count_total_likes = User_likes_flags::count_likes_topic($persona->userguid, 0);
	$count_total_tags = Jumppad::count_jumppads_topic('personal', $persona->userguid, 0, "T");
	$count_total_comments = Comments::count_user_comments($persona->userguid, 0);
	$count_total_knowcells = Jumppad::count_total_knowcells($persona->userguid);
	
	$choice = 1;
	$choice1 = '<li><a id="p1" class="active" href="persona.php?choice=1&user_id='.$persona->userguid.'">1</a></li>';
	$choice2 = '<li><a id="p2" href="persona.php?choice=2&user_id='.$persona->userguid.'">2</a></li>';
	$choice3 = '<li><a id="p3" href="persona.php?choice=3&user_id='.$persona->userguid.'">3</a></li>';
	if(isset($_GET['choice']) and $_GET['choice'] == 2)
		{
		$choice = 2;
		$choice1 = '<li><a id="p1" href="persona.php?choice=1&user_id='.$persona->userguid.'">1</a></li>';
		$choice2 = '<li><a id="p2" class="active" href="persona.php?choice=2&user_id='.$persona->userguid.'">2</a></li>';
		$choice3 = '<li><a id="p3" href="persona.php?choice=3&user_id='.$persona->userguid.'">3</a></li>';
		}
	else if(isset($_GET['choice']) and $_GET['choice'] == 3)
		{
		$choice = 3;
		$choice1 = '<li><a id="p1" href="persona.php?choice=1&user_id='.$persona->userguid.'">1</a></li>';
		$choice2 = '<li><a id="p2" href="persona.php?choice=2&user_id='.$persona->userguid.'">2</a></li>';
		$choice3 = '<li><a id="p3" class="active" href="persona.php?choice=3&user_id='.$persona->userguid.'">3</a></li>';
		}

	$count_know_tags 	  = 0;
	$count_know_comments  = 0;
	$count_know_views     = 0;
	$count_know_curations = 0;
	$ktopicid = Jumppad::knowledge_interest($persona->userguid, "K", $choice); 
	if($ktopicid != 0)
		{
		$ktopic = Topic::find_by_id($ktopicid);
		$ktopicdesc = $ktopic->topic;
		$count_know_curations = Jumppad::count_jumppads_topic('personal', $persona->userguid, $ktopicid, "");
		$count_know_tags = Jumppad::count_jumppads_topic('personal', $persona->userguid, $ktopicid, "T");
		$count_know_views = Userview::count_views_topic($persona->userguid, $ktopicid);
		$count_know_comments = Comments::count_comments_topic($persona->userguid, $ktopicid);
		$know_level = calculate_level_topic_know($count_know_curations, $count_know_views);
		$kstep_num = $know_level[2];
		$next_know_rank = nextlevel($know_level[1]);
		$kcount_likes = User_likes_flags::count_likes_topic($persona->userguid, $ktopicid);
		}
	else
		{
		$ktopicdesc = "None";
		$know_level[0] = 0;
		$know_level[1] = 'Newbie';
		$kstep_num = 1;
		$next_know_rank = 'Newbie';
		$kcount_likes = 0;
		}
	$k_level_ul1 = '<li>1</li>';
	$k_level_ul2 = '<li>2</li>';
	$k_level_ul3 = '<li>3</li>';
	if($kstep_num == 1)
		{
		$k_level_ul1 = '<li class="current">1</li>';
		}
	else if($kstep_num == 2)
		{
		$k_level_ul2 = '<li class="current">2</li>';
		}
	else if($kstep_num == 3)
		{
		$k_level_ul3 = '<li class="current">3</li>';
		}
		
	if($know_level[1] == 'Newbie' or $know_level[1] == 'Recruit' or $know_level[1] == 'Apprentice' or $know_level[1] == 'Protégé')
		{
		$krankings1 = '<li>1. Newbie</li>';
		$krankings2 = '<li>2. Recruit</li>';
		$krankings3 = '<li>3. Apprentice</li>';
		$krankings4 = '<li>4. Protégé</li>';
		if($know_level[1] == 'Newbie')
			{
			$krankings1 = '<li class="current">1. Newbie</li>';
			}
		if($know_level[1] == 'Recruit')
			{
			$krankings2 = '<li class="current">2. Recruit</li>';
			}
		if($know_level[1] == 'Apprentice')
			{
			$krankings3 = '<li class="current">3. Apprentice</li>';
			}
		if($know_level[1] == 'Protégé')
			{
			$krankings4 = '<li class="current">4. Protégé</li>';
			}
		}
	if($know_level[1] == 'Warrior' or $know_level[1] == 'Samurai' or $know_level[1] == 'Ninja' or $know_level[1] == 'Knight')
		{
		$krankings1 = '<li>1. Warrior</li>';
		$krankings2 = '<li>2. Samurai</li>';
		$krankings3 = '<li>3. Ninja</li>';
		$krankings4 = '<li>4. Knight</li>';
		if($know_level[1] == 'Warrior')
			{
			$krankings1 = '<li class="current">1. Warrior</li>';
			}
		if($know_level[1] == 'Samurai')
			{
			$krankings2 = '<li class="current">2. Samurai</li>';
			}
		if($know_level[1] == 'Ninja')
			{
			$krankings3 = '<li class="current">3. Ninja</li>';
			}
		if($know_level[1] == 'Knight')
			{
			$krankings4 = '<li class="current">4. Knight</li>';
			}
		}
	if($know_level[1] == 'Master' or $know_level[1] == 'Wizard' or $know_level[1] == 'Oracle' or $know_level[1] == 'Legend')
		{
		$krankings1 = '<li>1. Master</li>';
		$krankings2 = '<li>2. Wizard</li>';
		$krankings3 = '<li>3. Oracle</li>';
		$krankings4 = '<li>4. Legend</li>';
		if($know_level[1] == 'Master')
			{
			$krankings1 = '<li class="current">1. Master</li>';
			}
		if($know_level[1] == 'Wizard')
			{
			$krankings2 = '<li class="current">2. Wizard</li>';
			}
		if($know_level[1] == 'Oracle')
			{
			$krankings3 = '<li class="current">3. Oracle</li>';
			}
		if($know_level[1] == 'Legend')
			{
			$krankings4 = '<li class="current">4. Legend</li>';
			}
		}
	
	$count_interest_tags     = 0;
	$count_interest_comments  = 0;
	$count_interest_views     = 0;
	$count_interest_curations = 0;
	
	$itopicid = Userview::top_interest($persona->userguid, 1, $choice); 
	if($itopicid != 0)
		{
		$itopic = Topic::find_by_id($itopicid);
		$itopicdesc = $itopic->topic;
		$count_interest_curations = Jumppad::count_jumppads_topic('personal', $persona->userguid, $itopicid, "");
		$count_interest_tags = Jumppad::count_jumppads_topic('personal', $persona->userguid, $itopicid, "T");
		$count_interest_views = Userview::count_views_topic($persona->userguid, $itopicid);
		$count_interest_comments = Comments::count_comments_topic($persona->userguid, $itopicid);
		$interest_level = calculate_level_topic_know($count_interest_curations, $count_interest_views);
		$istep_num = $interest_level[2];
		$next_interest_rank = nextlevel($interest_level[1]);
		$icount_likes = User_likes_flags::count_likes_topic($persona->userguid, $itopicid);
		}
	else
		{
		$itopicdesc = "None";
		$interest_level[0] = 0;
		$interest_level[1] = 'Newbie';
		$istep_num = 1;
		$next_interest_rank = 'Newbie';
		$icount_likes = 0;
		}
	$i_level_ul1 = '<li>1</li>';
	$i_level_ul2 = '<li>2</li>';
	$i_level_ul3 = '<li>3</li>';
	if($istep_num == 1)
		{
		$i_level_ul1 = '<li class="current">1</li>';
		}
	else if($istep_num == 2)
		{
		$i_level_ul2 = '<li class="current">2</li>';
		}
	else if($istep_num == 3)
		{
		$i_level_ul3 = '<li class="current">3</li>';
		}
		
	if($interest_level[1] == 'Newbie' or $interest_level[1] == 'Recruit' or $interest_level[1] == 'Apprentice' or $interest_level[1] == 'Protégé')
		{
		$irankings1 = '<li>1. Newbie</li>';
		$irankings2 = '<li>2. Recruit</li>';
		$irankings3 = '<li>3. Apprentice</li>';
		$irankings4 = '<li>4. Protégé</li>';
		if($interest_level[1] == 'Newbie')
			{
			$irankings1 = '<li class="current">1. Newbie</li>';
			}
		if($interest_level[1] == 'Recruit')
			{
			$irankings2 = '<li class="current">2. Recruit</li>';
			}
		if($interest_level[1] == 'Apprentice')
			{
			$irankings3 = '<li class="current">3. Apprentice</li>';
			}
		if($interest_level[1] == 'Protégé')
			{
			$irankings4 = '<li class="current">4. Protégé</li>';
			}
		}
	if($interest_level[1] == 'Warrior' or $interest_level[1] == 'Samurai' or $interest_level[1] == 'Ninja' or $interest_level[1] == 'Knight')
		{
		$irankings1 = '<li>1. Warrior</li>';
		$irankings2 = '<li>2. Samurai</li>';
		$irankings3 = '<li>3. Ninja</li>';
		$krankings4 = '<li>4. Knight</li>';
		if($interest_level[1] == 'Warrior')
			{
			$irankings1 = '<li class="current">1. Warrior</li>';
			}
		if($interest_level[1] == 'Samurai')
			{
			$irankings2 = '<li class="current">2. Samurai</li>';
			}
		if($interest_level[1] == 'Ninja')
			{
			$irankings3 = '<li class="current">3. Ninja</li>';
			}
		if($interest_level[1] == 'Knight')
			{
			$irankings4 = '<li class="current">4. Knight</li>';
			}
		}
	if($interest_level[1] == 'Master' or $interest_level[1] == 'Wizard' or $interest_level[1] == 'Oracle' or $interest_level[1] == 'Legend')
		{
		$irankings1 = '<li>1. Master</li>';
		$irankings2 = '<li>2. Wizard</li>';
		$irankings3 = '<li>3. Oracle</li>';
		$irankings4 = '<li>4. Legend</li>';
		if($interest_level[1] == 'Master')
			{
			$irankings1 = '<li class="current">1. Master</li>';
			}
		if($interest_level[1] == 'Wizard')
			{
			$irankings2 = '<li class="current">2. Wizard</li>';
			}
		if($interest_level[1] == 'Oracle')
			{
			$irankings3 = '<li class="current">3. Oracle</li>';
			}
		if($interest_level[1] == 'Legend')
			{
			$irankings4 = '<li class="current">4. Legend</li>';
			}
		}
	
	
	//count total curations
	$curations = number_format(Jumppad::count_jumppads('personal', $persona->userguid,0), 0);
	//count total followers
	$followers_count = number_format(User_follows::count_followers($persona->userguid, 2), 0);
	//count total following
	$following_count = number_format(User_follows::count_followers($persona->userguid, 1), 0);

	if($persona->avatar != '')
		$avatar = '<img src="retrieveUserAvatar.php?id='.$persona->userguid.'" alt="" width="70" height="70" />'; 
	else
		$avatar = '<img src="../theme/img/ui/icon-avatar.png" alt="" width="70" height="70" />';

//load view
$nav_search_discover = 'jumppad.php';
$currentDiscover='';
$currentPersona=' class="current" ';
$currentSetting='';


echo '
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	
	<title>SO:KNO&trade; | PersonaGraf&trade;</title>
';

include('../views/main_header.php');
include('../views/main_header_div.php');
$in = 1;
if(isset($_GET['in']))
	$in = $_GET['in'];
	
if($in == 1)
	include('../views/persona.php');
if($in == 2)
	include('../views/library.php');
if($in == 3)
	include('../views/connections.php');

//return to current session setting...	
	$session->set_user_view($curr_view);
/*	
echo '<pre>';
print_r($cells);
echo '</pre>';
*/
?>
