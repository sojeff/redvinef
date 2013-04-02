<?php

if (!empty($_POST))
{
	$collect_posted='$_POST=array(';
	foreach($_POST as $key=>$value)
	{
		$collect_posted.="'$key'=>'$value', ";
	}
	$collect_posted = substr($collect_posted, 0,-1) . ");";
        $validation_errors="";//$collect_posted;
}
else
{
	$collect_posted="No POST.";
        $validation_errors="";
}
//echo $collect_posted;
require_once('../libraries/initialize.php');



if(!$session->is_logged_in()) { redirect_to("login.php"); }
//get an instance of the logged in user
	$user = User::find_by_id($_SESSION['user_id']);
	$persona = $user;

	$find_level = User_levels::find_by_id($user->userguid);
	$level_array = calculate_level($user, $find_level);
	$following = false;
	$following = User_follows::get_followers($user->userguid);
	$userguid = $user->userguid;
	
	if($persona->avatar != '')
		$avatar = '<img src="retrieveUserAvatar.php?id='.$persona->userguid.'" alt="" width="70" height="70" />'; 
	else
		$avatar = '<img src="../theme/img/ui/icon-avatar.png" alt="" width="70" height="70" />';

	$private_array = array();
	$pvtarray = array();
	if($user->private_cell_name != '')
		{
		$qu_private = "select distinct p.privateid from private_groups_members as p, topics as t 
					   where p.userguid = '$userguid' and t.privateid = p.privateid order by t.topic";
		$resultpr = mysql_query($qu_private);

		if(!$resultpr)
			{
			echo '<br/>bad sql: '.mysql_error();
			}
		$private_array = db_result_to_array($resultpr);
		$ipvt = 0;
		foreach($private_array as $pa)
			{
			$privateid = $pa['privateid'];
			$qu_pvt_topic_name = "select topic from topics where privateid = '$privateid' ";
			$resultpvttopic = mysql_query($qu_pvt_topic_name);
			$result2pvt = db_result_to_array($resultpvttopic);
			foreach($result2pvt as $rowtopic)
				{
				$pvttopic = trim($rowtopic['topic']);
				$pvtarray[$ipvt] = $pvttopic;
				$ipvt++;
				}
			}
		}


	$myview = $session->user_view;
	if($session->user_view == 'private')
		$myview = $user->private_cell_name;
	$private_exists = '';
	if(isset($user->private_cell_name))
		$private_exists = $user->private_cell_name;
	$private_edit = '';
	if(isset($user->can_create_private))
		$private_edit = $user->can_create_private;
        if (isset($user->can_create_private)){$display_admin=$user->can_create_private;}
        
        //$validation_errors=$validation_errors . "<br><br>Adding Admin Status for Testing.<br>";
        //$display_admin=TRUE;
        
//---- uploads -----------------------------------------------
//upload image for personaimage
$upload_personaimage='personaimage';
if (isset($_FILES[$upload_personaimage]['name'])) {
    $filename = $_FILES[$upload_personaimage]['name'];
}

if (isset($filename)) {
    //$personalimage=$_POST['personaimage'];
    require_once('upload_avatar.php');
    $upload_avatar_result = function_upload_avatar($user->userguid, $upload_personaimage);
    if ($upload_avatar_result == 'Incorrect file type uploaded. "" files not accepted. Please upload an image file.') {
        $uploaded_results_string = "File size too large; please upload a file that is less than 2 megabytes.";
        //$validation_errors .="<BR>if incorrect, too large";
    } else if ($upload_avatar_result <> "") {
        $uploaded_results_string = $upload_avatar_result;
        //$validation_errors .="<BR>else if successful upload";
    } else {
        $display_crop = TRUE;
        //$validation_errors .="<BR>crop = true ...else ";
    }
    //$validation_errors .="<BR> this should alwasy run during upload, but not during crop";
//    $uploaded_results_string = testing('charge') . " plus " ;//. $uploaded_results_string;
     }    
//$validation_errors .="<BR> this  alwasy run after upload, and before crop";
     
//crop picture for personimage
if (isset($_POST['cropped']) && $_POST['cropped'] == "cropped") {
    require_once('upload_avatar.php');
    $upload_avatar_result = function_upload_avatar($user->userguid, $upload_personaimage, 'yes');
    // $upload_avatar_result=function_upload_avatar(2, 'bil','yes'); 
    // move this so cancel it here
    $validation_errors .= $upload_avatar_result;
}
 //upload documents for admin
 $upload_admindoc="admindoc";
 if (isset($_FILES[$upload_admindoc]['name']))   
    {
    require_once('upload_docs.php');
   $uploaded_results_string = upload_documents($user->userguid, $upload_admindoc);  
   if(SERVERPD == 'Prod')
   		$url = 'https://'.$_SERVER['SERVER_NAME'] .DS. 'redvinef' .DS. 'userdata'.DS. 'files'.DS. $userguid .DS. $_FILES[$upload_admindoc]['name'];
   else
   		$url = 'http://'.$_SERVER['SERVER_NAME'] .DS. 'redvinef' .DS. 'userdata'.DS. 'files'.DS. $userguid .DS. $_FILES[$upload_admindoc]['name'];
   $url = htmlentities($url);
   $title = trim($_POST['title']);
   $private_topic = trim($_POST['private']);
// ******************   
	if(1==1)
		{
		$qu_topic = "select * from topics where topic = '$private_topic'";
//echo "<br/>sel ".$qu_topic;
		$result = mysql_query($qu_topic);
		$result2 = db_result_to_array($result);
		foreach($result2 as $rowtopic)
			{
			$topicid = $rowtopic['topicid'];
			$privateid = $rowtopic['privateid'];
			}
		$foundcontent = false;
		$qu_content = "select * from content where url = '$url' limit 1"; //need to use the content type too...
		$result = mysql_query($qu_content);
		$result2 = db_result_to_array($result);
		foreach($result2 as $rowcontent)
			{
			$contentid = $rowcontent['contentid'];
			$foundcontent = true;
			}
		if(!$foundcontent)
			{
			$qu_insert = "insert into content set   url       = '$url',
													title     = '$title',
													privateid = '$privateid',
													userguid  = '$userguid'
													";
	//echo "<br/>ins ".$qu_insert;
			$result = mysql_query($qu_insert);
			$contentid = mysql_insert_id();
				//save the images for this content... up to 4 images save smaller images in image, image2, image3, image4
				//save full image in image_full, image2_full, image3_full, image4_full
				//no sense saving 2 copies of full image until i figure out scaling...
					
				//now go get the article contents with READABILITY
				//$magic_quotes = ini_get("magic_quotes_gpc") ? true : false;
				
				//if($magic_quotes)
				//	$request_url = stripslashes($url);
				//else
					$request_url = $url;
		/*	
				$request_url_hash = md5($url);
	
				$handle = curl_init();
				curl_setopt_array($handle, array(
					CURLOPT_USERAGENT => USER_AGENT,
					CURLOPT_HEADER  => false,
					CURLOPT_HTTPGET => true,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_TIMEOUT => 30,
					CURLOPT_URL => $request_url
				));
				
					$source = curl_exec($handle);
					curl_close($handle);
				
	
				preg_match("/charset=([\w|\-]+);?/", $source, $match);
				$charset = isset($match[1]) ? $match[1] : 'utf-8';
				
				$Readability = new Readability($source, $charset);
				$Data = $Readability->getContent();
				$output_type = 'html';
				switch($output_type) {
					case 'json':
						//header("Content-type: text/json;charset=utf-8");
						$Data['url'] = $url;
						//echo json_encode($Data);
						break;
				
					case 'html': default:
						//header("Content-type: text/html;charset=utf-8");
				
						$title   = $Data['title'];
						$content = $Data['content'];
	//echo '<br/>url: '.$request_url;
	//echo '<br/>content: '.$content;
				
						//include 'template/reader.html';
				}
					
				$dt = date('Y-m-d G:i:s');
				//save the content article PAGE
				//if(isset($_POST['contentto']) or isset($_POST['contentfrom']))
				$readit = strpos($content, 'Sorry, readability was unable');
				
				*/
				
				if(isset($content) and $content != '' and $readit === false and 1==2)
					{
					//$contentto = mb_convert_encoding($content);
					$content = addslashes($content);
						
					$sql = "insert into article_page set contentid = '$contentid',
														 page      = 1,
														 body      = '$content',
														 userguid  = '$userguid',
														 date_created = '$dt' ";
					if(!$result = mysql_query($sql)and $userguid == '1004833642')
						{
						echo '<br/><br/>Error: '.mysql_error();
						echo '<br/><br/>article SQL: '.$sql.'<br/>';
						}
					}
	
			}
		
		$mydate = date('Y-m-d G:i:s');
		
		$qu_insert = "insert into curations set userguid  = '$userguid',
												topicid   = '$topicid',
												contentid = '$contentid',
												tags      = '',
												privateid = '$privateid',
												date_created = '$mydate'
												";
		$result = mysql_query($qu_insert);
	//echo "<br/>ins ".$qu_insert;
			
		//insert or update the userlevels - tracks when a user does something... in this case
		//the user is incrementing their curation count.
		$qu_user_level = "select * from user_levels where userguid = '$userguid' limit 1";
		$result = mysql_query($qu_user_level);
		if(mysql_num_rows($result) > 0)
			{
			$qu_update = "update user_levels set curations_count = curations_count + 1 
							where userguid = '$userguid' limit 1";
			$result = mysql_query($qu_update);
			}
		else
			{
			$qu_insert = "insert into user_levels set   userguid = '$userguid',
														curations_count  = 1
														";
			$result = mysql_query($qu_insert);
			}
		}
	
// *******************
   //$uploaded_results_string = $uploaded_results_string . '<br>Current script owner: ' . get_current_user() . " " . getmygid() . " " . dirname( __FILE__ );
//    $uploaded_results_string = testing('charge') . " plus " ;//. $uploaded_results_string;
     }      
if (isset($uploaded_results_string)){
    $validation_errors .= "<BR>" .$uploaded_results_string;
    }    
 // --- end uploads -------------------
    
 
    
 // --- handle POST -------------------------
    
 if (isset($_POST['info_update']) ? TRUE : FALSE) {
    $validation_errors .= "". update_info($user);
}

if (isset($_POST['email_update']) ? TRUE : FALSE) {

    //$validation_errors .= "<br><BR> email update";
    $emailresult = update_email($user);
    if ($emailresult <> "") {
        $validation_errors .= "" . $emailresult;
    }
}

if (isset($_POST['passwd_update']) ? TRUE : FALSE) {

    //$validation_errors .= "<br><BR> pw_update BEFORE";
    $pwresult = update_password($user);

    if ($pwresult <> "") {
        $validation_errors .= "" . $pwresult;
        //$validation_errors .= "<br><br>" . $pwresult;
    }
}
     
if($validation_errors == '1')
	$validation_errors = "";
    
    
 // --- end handle POST -------------------------

	$cell = Jumppad::current_cell($persona->userguid);
	//now prep the current curation for content or thread

	$not_in_my_thread_array = true;
	$commenttitle = "Blank Title";
	$maincurationid = 0;  //set up variables for hyperlinks
	$maincommentid  = 0;
	//get the full curation record...
	if($cell->contentid != 0) //get the related content
		{
		$content = Cell::find_by_id($cell->contentid); 
		}
	if(($cell->content_type == 1 or $cell->content_type == 2) and $cell->commentid != 0) //comment
		{
		$comment = Comments::find_by_id($cell->commentid);
		
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
		$celltitle = substr(str_replace("www.","",parse_url($content->url,PHP_URL_HOST)),0,28);
		$celltitle = str_replace("m.youtube.com","youtube.com",$celltitle);
		$cellhref = 'viewcontent.php?contentid='.$cell->contentid.'&curationid='.$cell->curationid.'&userguid='.$cell->userguid;
		$cellimagesize = $content->cell_image_size($cell->contentid);
		
		if($cellimagesize != '' and $image_id = $content->cell_image($cell->contentid)) 
			{
			$width = $content->cell_image_size($cell->contentid);
			if(1==1)
				{
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
				{
				$image_display = '<img src="../theme/img/ui/placeholder-thumb.png" alt="" width="228" height="128">'; 
				}
			}
		else
			$image_display = '<img src="../theme/img/ui/placeholder-thumb.png" alt="" width="228" height="128">'; 
		
		if(strlen($content->title) >= 62)
			$body = substr($content->title,0,59) . "..."; 
		else
			$body = $content->title;
		
		//now do the stats for this curationid
		$time = diff_times($content->date_created); 
		if(substr($time,0,2) != '1 ')
			$add = 's';
		else
			$add = '';
		
		}
	else //comment needs variables set for view ******************************
		{
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
		if($commenttitle == "Blank Title" and $comment->contentid != 0)
			{
			//go get the content Title
			$thiscontent = Cell::find_by_id($comment->contentid);
			if(strlen($thiscontent->title) < 62)
				$commenttitle = $thiscontent->title;
			else
				$commenttitle = substr($thiscontent->title, 0, 59) . "...";
			}

		$find_level = User_levels::find_by_id($persona->userguid);
		$level_array = calculate_level($persona, $find_level);

		$following = false;
		$following = User_follows::get_followers($persona->userguid);

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

		}

    
//load view
$nav_search_discover = 'jumppad.php';
//set current triangle to settings
$currentDiscover='';
$currentPersona='';
$currentSetting='class="current" ';
echo '
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	
	<title>SO:KNO&trade; | Settings</title>';

include('../views/main_header.php');
include('../views/main_header_div.php');
include('../views/settings.v.php');


function update_info($user) {
    $user->first_name = $_POST['first_name'];
    $user->last_name = $_POST['last_name'];
    $user->gender = strtolower($_POST['gender']);
    $user->birthday = implode("-", array($_POST['year'], $_POST['month'], $_POST['day']));
    $user->currentlocationcity = $_POST['city'];
    $user->currentlocationzip = $_POST['zip'];
    $user->currentlocationstate = $_POST['state'];
    $inforesult = $user->save();
    return $inforesult;
    if ($inforesult == TRUE) {
        return "Your persona information has been updated.";
    } else {
        return "There may have been an error; your persona information has not been updated.  Did you actually change anything?";
    }
}
function update_password($user) {
    if (($_POST['newpassword'] == $_POST['passwordconfirm']) && $_POST['newpassword'] <> "") {
        $user->password = $_POST['newpassword'];
        $pwresultsave = $user->save();
        if ($pwresultsave == TRUE) {
            return "Your password has been updated.";
        } else {
            return "There was an error.  Your password has not been updated. Is it possible you used your old password again?";
        }
    } else {
        return "Your passwords do not match.";
    }
}

function update_email($user) {
    if (isValidEmail($_POST['email']) == 1) {
        $user->email = $_POST['email'];
        $emailresult = $user->save();
        if ($emailresult == TRUE) {
            return "Your email has been updated to " . $user->email;
        }
        else {
            return "Your email has NOT been updated to " . $user->email . " Is it possible you used the same one?";
        }
    }
    return "Your email is not well formed.";
}

?>
