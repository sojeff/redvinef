<?php
	error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_startup_errors', 'On');
ini_set('display_errors', 'On');

if(isset($_GET['guid']) and $_GET['guid'] != '' and $_GET['guid'] != 0)
	{
	$guid = trim($_GET['guid']);

	$intwomonths = 60 * 60 * 24 * 60 + time();
	$f = setcookie("guid", $guid, $intwomonths);
	}
if(isset($_POST['community']) and $_POST['community'] != '')
	{
	foreach($_POST['community'] as $community)
		{
		$community = htmlspecialchars(trim($community));
		}

	$intwomonths = 60 * 60 * 24 * 7 + time();
	$f = setcookie("community", $community, $intwomonths);
	}
if(isset($_POST['tag-community']) and $_POST['tag-community'] != '')
	{
	$tagarray_load = explode(', ',$_POST['tag-community']);
	foreach($tagarray_load as $tags)
		{
		$community = htmlspecialchars(trim($tags));
		}

	$intwomonths = 60 * 60 * 24 * 7 + time();
	$f = setcookie("community", $community, $intwomonths);
	}
if(isset($_POST['tag-content']) and $_POST['tag-content'] != '' and $_POST['tag-content'] != 'Separate each Name with Comma')
	{
		$tags2 = htmlspecialchars(trim($_POST['tag-content']));

	$intwomonths = 60 * 60 * 24 * 7 + time();
	$f = setcookie("tags2", $tags2, $intwomonths);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>So KNO | Button</title>
	<link rel="stylesheet" href="css/reset.css">
	<link rel="stylesheet" href="css/bookmark.css">
	
	<script type="text/javascript" src="js/libs/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="js/libs/jquery.asmselect.js"></script>
	<script type="text/javascript">
	
		$(document).ready(function() {
			clearTextField( jQuery("#tag-content, #tag-community") );
			
			$('.button').toggle(function() {
				$(this).addClass("current");
			}, function() {
				$(this).removeClass("current");
			});
			
			$("select[multiple]").asmSelect({
                addItemTarget: 'bottom',
                animate: true,
                highlight: true
            });
		});
		
		function clearTextField( field ){
			field.focus( function(){
				jQuery(this).addClass('active');
				if ( this.value == this.defaultValue ){
					this.value = '';
				}
			});

			field.blur( function(){
				jQuery(this).removeClass('active');
				if( this.value == '' ){
					this.value = this.defaultValue;
				}
			});
		}
			
	</script>

</head>
<?php
echo '<body>';

	include_once "lewebkit.php";
    include_once "display_fns.php";
    include_once "display_login_fns.php";
	$db_conn = false;
	$db_conn = le_dbconnect();
	if(!$db_conn)
		{
		echo '<br><h2>Unable to connect to database!';
		exit;
		}
		
	if(isset($_COOKIE['guid']) and 
			 $_COOKIE['guid'] != '' and 
			 !isset($guid))
		{
		$guid = $_COOKIE['guid'];
		}
	if(isset($_COOKIE['community']) and 
			 $_COOKIE['community'] != '' and 
			 !isset($community))
		{
		$community = $_COOKIE['community'];
		}
	if(isset($_COOKIE['tags']) and 
			 $_COOKIE['tags'] != '' and 
			 !isset($tags))
		{
		$tags = $_COOKIE['tags'];
		}
if(isset($_POST) and $guid == '1004833642' and 1==2) //display the POST var contents...
	{
	echo '<pre>';
	print_r($_POST);
	echo '</pre>';
	}
//	echo '<br/>Community: '.$community;
	
			
			if(isset($_GET['url']) or (isset($_POST['community']) and $_POST['community'] == ''))
				{
				if(isset($_POST['community']) and $_POST['community'] == '')
					{
					$url = $_POST['url'];
					$title = $_POST['title'];
					$community = str_replace(", Separate each Name with Comma","",$_POST['community']);
					$community = str_replace("Separate each Name with Comma, ","",$community);
					$community = str_replace("Separate each Name with Comma","",$community);
					$tags = str_replace(", Separate each Name with Comma","",$_POST['tags']);
					$tags = str_replace("Separate each Name with Comma, ","",$tags);
					$tags = str_replace("Separate each Name with Comma","",$tags);
					$tags2 = str_replace(", Separate each Name with Comma","",$_POST['tags2']);
					$tags2 = str_replace("Separate each Name with Comma, ","",$tags2);
					$tags2 = str_replace("Separate each Name with Comma","",$tags2);
					$tags2 = str_replace(", Separate each Keyword with Comma","",$tags2);
					$tags2 = str_replace("Separate each Keyword with Comma, ","",$tags2);
					$tags2 = str_replace("Separate each Keyword with Comma","",$tags2);
					$guid = $_POST['guid'];
					$again = 'Y';
					}
				else
					{
					$url = $_GET['url'];
					$title = $_GET['title'];
					$guid = $_GET['guid'];
					$again = 'N';
					if(!isset($community))
						$community = '';
					if(!isset($tags))
						$tags = '';
					if(!isset($tags2))
						$tags2 = '';
					}

				$qu_user = "select * from users where userguid = '$guid'";
				$result = mysql_query($qu_user);
				$result2 = db_result_to_array($result);
				foreach($result2 as $rowusers)
					{
					$first_name = $rowusers['first_name'];
					}
					
				//pull the unique tags from this user
				$tagsarray = array();
				$qu_topics = "select topicid from curations where curationid in
								(select  max(curationid) 
								from curations 
								group by topicid)  and userguid = '$guid' order by curationid desc limit 150";
	//echo '<br/>qutopics: '.$qu_topics;
	$result = mysql_query($qu_topics);
				$result2 = db_result_to_array($result);
				$i = 0;
				foreach($result2 as $rowtopicid)
					{
					$topicid = trim($rowtopicid['topicid']);
					$qu_topic_name = "select topic from topics where topicid = '$topicid' ";
					$resulttopic = mysql_query($qu_topic_name);
					$result2topic = db_result_to_array($resulttopic);
					foreach($result2topic as $rowtopic)
						{
						$topic = trim($rowtopic['topic']);
						if(strpos("Separate each Name with Comma", $topic) === false)
							{
							$tagsarray[$i] = $topic;
							$i++;
							}
	//echo '<br/>topic: '.$topic;
						}
					}

				display_bookmark($url, $title, $community, $tagsarray, $tags, $guid);
				
				}
			else if(isset($_POST['book']))
				{
				$url = addslashes(stripslashes(trim($_POST['url'])));
				$title = addslashes(stripslashes(trim($_POST['title'])));
				$tags = addslashes(stripslashes(trim($_POST['tag-community'])));
				$tags = str_replace(", Separate each Name with Comma","",$tags);
				$tags = str_replace("Separate each Name with Comma, ","",$tags);
				$tags = str_replace("Separate each Name with Comma","",$tags);
				
				$tags2 = addslashes(stripslashes(trim($_POST['tag-content'])));
				$tags2 = str_replace(", Separate each Keyword with Comma","",$tags2);
				$tags2 = str_replace("Separate each Keyword with Comma, ","",$tags2);
				$tags2 = str_replace("Separate each Keyword with Comma","",$tags2);
				$guid = trim($_POST['guid']);

				//$community = html_entity_decode(trim($_POST['community']));
				
				if($guid == '')
					$guid = '700000949195447';  //give dan rietz guid if it comes in blank...
				
				// get the community id 1st...
				//find the community?
				if(!empty($_POST['community']) and $_POST['community'] != '' and $_POST['community'] != "Separate each Name with Comma")
					{
					foreach($_POST['community'] as $community)
						{
						if($community != '')
							{
							$topicid = '';
							$community = html_entity_decode(trim($community));
							$foundcommunity = false;
							$qu_topic = "select * from topics where topic = '$community'";
							$result = mysql_query($qu_topic);
							$result2 = db_result_to_array($result);
							foreach($result2 as $rowtopic)
								{
								$topicid = $rowtopic['topicid'];
								$foundcommunity = true;
								}
							if(!$foundcommunity)
								{
								$qu_insert = "insert into topics set   topic      = '$community'  ";
								$result = mysql_query($qu_insert);
									
								$topicid = mysql_insert_id();
									
								}
							if($topicid != '')
								{
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
									$qu_insert = "insert into content set   url      = '$url',
																			title    = '$title'
																			";
									$result = mysql_query($qu_insert);
									$contentid = mysql_insert_id();
									}
								
								$qu_insert = "insert into curations set userguid = '$guid',
																		topicid  = '$topicid',
																		contentid = '$contentid',
																		tags     = '$tags2'
																		";
								$result = mysql_query($qu_insert);
								}
							}
						}
					}
				if(!empty($_POST['tag-community']) and $_POST['tag-community'] != '' and $_POST['tag-community'] != "Separate each Name with Comma")
					{
					$tagarray_load = explode(',',$_POST['tag-community']);
					foreach($tagarray_load as $community)
						{
						$topicid = '';
						$community = html_entity_decode(trim($community));
						$foundcommunity = false;
						$qu_topic = "select * from topics where topic = '$community'";
						$result = mysql_query($qu_topic);
						$result2 = db_result_to_array($result);
						foreach($result2 as $rowtopic)
							{
							$topicid = $rowtopic['topicid'];
							$foundcommunity = true;
							}
						if(!$foundcommunity)
							{
							$qu_insert = "insert into topics set   topic      = '$community'  ";
							$result = mysql_query($qu_insert);
								
							$topicid = mysql_insert_id();
								
							}
						if($topicid != '')
							{
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
								$qu_insert = "insert into content set   url      = '$url',
																		title    = '$title'
																		";
								$result = mysql_query($qu_insert);
								$contentid = mysql_insert_id();
								}
							
							$qu_insert = "insert into curations set userguid = '$guid',
																	topicid  = '$topicid',
																	contentid = '$contentid',
																	tags     = '$tags2'
																	";
							$result = mysql_query($qu_insert);
							}
						}
					}
				//if($result)
				if(1==1)
					{
					$qu_user = "select * from users where userguid = '$guid'";
					$result = mysql_query($qu_user);
					$result2 = db_result_to_array($result);
					foreach($result2 as $rowusers)
						{
						$first_name = $rowusers['first_name'];
						}
					$url = htmlspecialchars(stripslashes($_POST['url']));
					$title = htmlspecialchars(stripslashes($_POST['title']));
					$community = '';
					if(!empty($_POST['community']))
						$community = $_POST['community'];
					$community2 = '';
					if(!empty($_POST['tag-community']) and $_POST['tag-community'] != '' and $_POST['tag-community'] != "Separate each Name with Comma")
						{
						$community2 = explode(',',$_POST['tag-community']);
						}
					$tag_content = '';
					if(!empty($_POST['tag-content']) and $_POST['tag-content'] != 'Separate each Keyword with Comma')
						$tag_content = $_POST['tag-content'];
						
					
					}
				else
					{
					echo '<br/>Error! '.$qu_insert;
					}

				}
			else
				echo 'This page is invalid...';
			
		display_top_login();  //+1 <div tag
		display_bookmark_results($first_name, $url, $title, $community, $community2, $tag_content);
		display_footer();
?>