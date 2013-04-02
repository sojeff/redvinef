<?php
	error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL);
	ini_set('display_startup_errors', 'On');
	ini_set('display_errors', 'On');
/*
Personal knowcell entered
 [guid] => 1004833642
    [url] => http://www.phplivedocx.org/downloads/
    [title] => Downloads | LiveDocx in PHP - Zend_Service_LiveDocx
    [jumppad] => personal
    [personal] => knowtitle
    [knowcell_name] => My Knowledge Cell 
    [knowcell_name_pvt] => 
    [topics] => Design
    [tag-content] => tagggyy

selected a personal knowcell in drop DOWN - scuba diving
 [guid] => 1004833642
    [url] => http://www.phplivedocx.org/downloads/
    [title] => Downloads | LiveDocx in PHP - Zend_Service_LiveDocx
    [jumppad] => personal
    [personal] => Scuba Diving
    [knowcell_name] => 
    [knowcell_name_pvt] => 
    [topics] => Education
    [tag-content] => taggy 2
    
added private knowledge cell
 [guid] => 1004833642
    [url] => http://www.phplivedocx.org/downloads/
    [title] => Downloads | LiveDocx in PHP - Zend_Service_LiveDocx
    [jumppad] => private
    [private] => knowtitle
    [knowcell_name] => Added a new private knowledge cell
    [knowcell_name_pvt] => 
    [tag-content] => prv tags1

added private from drop down
 [guid] => 1004833642
    [url] => http://www.phplivedocx.org/downloads/
    [title] => Downloads | LiveDocx in PHP - Zend_Service_LiveDocx
    [jumppad] => private
    [private] => MKKK14 Child Psychology TTH 10am Mck
    [knowcell_name] => 
    [knowcell_name_pvt] => 
    [tag-content] => taggg pvt 2 


*/
	$submitted = false;

	if(isset($_POST['topics']) and $_POST['topics'] != '')
		{
		$private = 'N';
		$community = htmlspecialchars(trim($_POST['topics']));
		$community_global = $community; //use the image from the global community for the personal community...
		
		$intwomonths = 60 * 60 * 24 * 7 + time();
		$f = setcookie("community", $community, $intwomonths);
		if(isset($_POST['knowcell_name']) and trim($_POST['knowcell_name']) != '')
			{
			$personal_topic = stripslashes(trim($_POST['knowcell_name']));
			$personal_topic = str_replace('"',"",$personal_topic);
			$personal_topic = str_replace("'","",$personal_topic);
			$personal_create = 'Y';
			$private_topic = '';
			$intwomonths = 60 * 60 * 24 * 7 + time();
			$f = setcookie("personal_topic", $personal_topic, $intwomonths);
			$submitted = true;
			}
		if(isset($_POST['personal']) and $_POST['personal'] != 'knowtitle' and $_POST['personal'] != '')
			{
			$personal_topic = stripslashes(trim($_POST['personal']));
			$personal_topic = str_replace('"',"",$personal_topic);
			$personal_topic = str_replace("'","",$personal_topic);
			$personal_create = 'N';
			$private_topic = '';
			$intwomonths = 60 * 60 * 24 * 7 + time();
			$f = setcookie("personal_topic", $personal_topic, $intwomonths);
			$submitted = true;
			}
		}
	else  //this means it is a private knowcell entry
		{
		if(isset($_POST['private']) and $_POST['private'] != 'knowtitle')
			{
			$private = 'Y';
			$private_create = 'N';
			$private_topic = stripslashes(trim($_POST['private']));
			$private_topic = str_replace('"',"",$private_topic);
			$private_topic = str_replace("'","",$private_topic);
			$personal_topic = '';
			}
		if(isset($_POST['private']) and $_POST['private'] == 'knowtitle')
			{
			$private = 'Y';
			$private_create = 'Y';
			$private_topic = stripslashes(trim($_POST['knowcell_name']));
			$private_topic = str_replace('"',"",$private_topic);
			$private_topic = str_replace("'","",$private_topic);
			$personal_topic = '';
			}
		}
	if(isset($_POST['tag-content']) and $_POST['tag-content'] != '' and $_POST['tag-content'] != 'Separate each Name with Comma')
		{
		$tags2 = htmlspecialchars(trim($_POST['tag-content']));
	
		$intwomonths = 60 * 60 * 24 * 7 + time();
		$f = setcookie("tags2", $tags2, $intwomonths);
		}

	require_once('../libraries/initialize.php');
	require_once ('../libraries/extractPageElements/extractPageElements.php');	
	
	//set the userguid if we have looped around
	if(isset($_POST['guid']) and $_POST['guid'] != 0)
		$userguid = $_POST['guid'];
	else if(isset($_COOKIE['guid']) and 
			 $_COOKIE['guid'] != '' and 
			 !isset($userguid))
		{
		$userguid = $_COOKIE['guid'];
		}

	if(!isset($userguid))
		{
		echo '<br>You have not entered this page correctly...';
		echo '<pre>';
		print_r($_GET);
		echo '</pre>';
		}
	else
		{
		//get an instance of the logged in user
		$user = User::find_by_id($userguid);

/* this magic quotes doesn't work!!!
// we have magic quotes on our site

		$magic_quotes = ini_get("magic_quotes_gpc");
		
		if(!$magic_quotes)
			{
			$url = addslashes(trim($_POST['url']));
			$title = addslashes(trim($_POST['title']));
			if(isset($_POST['image']))
				$image = addslashes(trim($_POST['image']));
			else
				$image = null;
			$tags2 = addslashes(trim($_POST['tag-content']));
			}
		else
			{
			$url = trim($_POST['url']);
			$title = trim($_POST['title']);
			if(isset($_POST['image']))
				$image = trim($_POST['image']);
			else
				$image = null;
			$tags2 = trim($_POST['tag-content']);
			}
*/
		$url = trim($_POST['url']);
		$title = trim($_POST['title']);
		if(isset($_POST['image']))
			$image = trim($_POST['image']);
		else
			$image = null;
		$tags2 = trim($_POST['tag-content']);
		$tags2 = str_replace("\\","",$tags2);
		$tags2 = str_replace('"',"",$tags2);
		$tags2 = str_replace("'","",$tags2);
		$tags2 = str_replace(", Separate each Keyword with Comma","",$tags2);
		$tags2 = str_replace("Separate each Keyword with Comma, ","",$tags2);
		$tags2 = str_replace("Separate each Keyword with Comma","",$tags2);

		if(!empty($community)) //if private, $community is not set
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
/* this should never happen	because this should always be a global community 

			if(!$foundcommunity)
				{
				$qu_insert = "insert into topics set   topic = '$community'  ";
				$result = mysql_query($qu_insert);
				$topicid = mysql_insert_id();
				}
				
*/
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
															title    = '$title',
															userguid = '$userguid'
															";
					$result = mysql_query($qu_insert);
					$contentid = mysql_insert_id();
					
					//save the images for this content... up to 4 images save smaller images in image, image2, image3, image4
					//save full image in image_full, image2_full, image3_full, image4_full
					$mypageExtract = $_SESSION['mypageExtract'];
					$imgURL = array();
					$imgData = array();
					$size = array();
					$imagethumb = array();
				//echo '<pre>';
				//print_r($_POST);
				//print_r($mypageExtract['images']);
				//echo '</pre>';
					$k = 1;
					$y = 0;
					for ($i=0; $i<3; $i++)
						{
						if(isset($mypageExtract['images'][$i]))
							{
							if($image == $k)
								{
								$imgURL[$y] = $mypageExtract['images'][$i];
								$imgData[$y] = file_get_contents($imgURL[$y]);
								
								
								if (!$imgData[$y])
									{
									echo '<br/>imgURL'.$imgURL[$y];
									die("Error: Invalid File Specified");
									}
								if($imgData[$y])
									{
									$size[$y] = getimagesize($imgURL[$y]);
									
									$imgData[$y] = mysql_real_escape_string($imgData[$y]);
									
									if ($size[$y]['mime'] == '') // getimagesize failed to determine the file type
										{
										// write file data to a temporary file so we can get file info, such as mimetype
										$fileData = file_get_contents($mypageExtract['images'][$i]);
										$tmp=array_search('uri', @array_flip(stream_get_meta_data($GLOBALS[mt_rand()]=tmpfile())));
										file_put_contents($tmp, $fileData);
									
										$finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
										$size[$y]['mime'] = finfo_file($finfo, $tmp) . "\n";
										finfo_close($finfo);
										}
									//now go get the scaled down IMAGE
									$width = 228;  //actual cell is 225x96 this will scale down nicely after cropping
									$height = 128;
									$crop = 1;
								//echo '<br/>imageurl['.$y.']: '.$imgURL[$y];
								//echo '<br/>imgData[i]: '.$imgData[$i];
								//echo '<br/>width: '.$width;
								//echo '<br/>height: '.$height;
									$imagethumb[$y] = image_resize($imgURL[$y], $imgData[$y], $width, $height, $crop);
								//echo '<br/>y: '.$y;
									//$imagethumb[$y] = $imgData[$i];
									$y++;
									}
								}
							}
						$k++;
						}
					//now save the image(s)
					
					//no sense saving 2 copies of full image until i figure out scaling...
					$imgData[0] = '';
					$imgData[1] = '';
					$imgData[2] = '';
					$imgData[3] = '';
					//***********
				//	$sql =  ( image_id , image_type , image_size, image_name, contentid, image_url_orig, image, image_full,
				//										image2, image2_full, image2_size, image3, image3_full, image3_size, image4, image4_full, image4_size)
				//								VALUES ('', '{$size[0]['mime']}', '{$size[0][3]}', '{$imgURL[0]}', '$contentid', '$url', '{$imagethumb[0]}', '{$imgData[0]}',
				//										'{$imagethumb[1]}', '{$imgData[1]}', '{$size[1][3]}', '{$imagethumb[2]}', '{$imgData[2]}', '{$size[2][3]}', '{$imagethumb[3]}', '{$imgData[3]}', '{$size[3][3]}' )";
					$sql = "INSERT INTO content_image ( image_id , image_type , image_size, image_name, contentid, image_url_orig, image, image_full)
												VALUES ('', '{$size[0]['mime']}', '{$size[0][3]}', '{$imgURL[0]}', '$contentid', '$url', '{$imagethumb[0]}', '{$imgData[0]}')";
					
					if(!$error = mysql_query($sql) and $userguid == '1004833642')
						{
						echo '<br/><br/>Error: '.mysql_error();
						echo '<br/><br/>533 SQL: '.$sql.'<br/>';
						}
						
					//now go get the article contents with READABILITY
					
					//if($magic_quotes)
					//	$request_url = stripslashes($url);
					//else
						$request_url = $url;

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
					if(isset($content) and $content != '' and $readit === false)
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
				$qu_insert = "insert into curations set userguid = '$userguid',
														topicid  = '$topicid',
														contentid = '$contentid',
														date_created = '$mydate',
														tags     = '$tags2'
														";
				$result = mysql_query($qu_insert);
				
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
			}

//do the private content
		if($private == 'Y')
			{
		
			$community = $private_topic;
			if($community != '')
				{
				$topicid = '';
				$save_topic_image = "N";
				$community = html_entity_decode(trim($community));
				$foundcommunity = false;
				$qu_topic = "select * from topics where topic = '$community'";
//echo "<br/>sel ".$qu_topic;
				$result = mysql_query($qu_topic);
				$result2 = db_result_to_array($result);
				foreach($result2 as $rowtopic)
					{
					$topicid = $rowtopic['topicid'];
					$privateid = $rowtopic['privateid'];
					$foundcommunity = true;
					}
				if(!$foundcommunity and $private_create == 'Y') //create the private topic
					{
					// get the private topic we want to create
					$sql = "select index_part from private_user_topics where private_cell_name = '".$user->private_cell_name."'";
					$result = mysql_query($sql);
					$result2 = db_result_to_array($result);
					foreach($result2 as $in_put)
						{
						$index_part_prv_user_topic = $in_put['index_part'];
						$sql_ins = "insert into private_groups set private_user_index_part = $index_part_prv_user_topic,
																   userguid = $userguid, 
																   private_type = 'topic',
																   private_group_title = '$private_topic' ";
						if($result = mysql_query($sql_ins))
							{
							$privateid = mysql_insert_id();
							//now, insert the private_groups_members...
							$sql_ins_pgm = "insert into private_groups_members set private_user_index_part = $index_part_prv_user_topic, 
																				   privateid = $privateid,
																				   userguid   = $userguid,
																				   access     = 'admin' ";
							if($result = mysql_query($sql_ins_pgm))
								{
								$memberid = mysql_insert_id();
								//now make Dan and Jeff members of this new private cell...
								$sql_ins_pgm = "insert into private_groups_members set private_user_index_part = $index_part_prv_user_topic, 
																					   privateid = $privateid,
																					   userguid   = '1004833642',
																					   access     = 'admin' ";
								mysql_query($sql_ins_pgm);
								$sql_ins_pgm = "insert into private_groups_members set private_user_index_part = $index_part_prv_user_topic, 
																					   privateid = $privateid,
																					   userguid   = '700000949195454',
																					   access     = 'admin' ";
								mysql_query($sql_ins_pgm);
								mail("dan@getsokno.com, jeff@getsokno.com","Private Knowledge Cell Created ".$private_topic,
									 "Private Knowledge Cell Created: ".$private_topic."\r\nBy ".$user->name."\r\nID: ".$userguid."\r\nServer: ".SERVERPD,
									 'From: SO:KNO <support@getsokno.com>');
								if($index_part_prv_user_topic == '1000' and SERVERPD == 'Prod') // it's hartnell, so make Matt Coombs a member
									{
									$sql_ins_pgm = "insert into private_groups_members set private_user_index_part = $index_part_prv_user_topic, 
																						   privateid = $privateid,
																						   userguid   = '700000949195503',
																						   access     = 'admin' ";
									mysql_query($sql_ins_pgm);
									$sql_ins_pgm = "insert into private_groups_members set private_user_index_part = $index_part_prv_user_topic, 
																						   privateid = $privateid,
																						   userguid   = '700000949195508',
																						   access     = 'admin' ";
									mysql_query($sql_ins_pgm);
									//mail('','','','');
									}
								//now, create the topic as a private topic... only this user has access
								$folder = $_SERVER['DOCUMENT_ROOT'].'/knocell_images';
								$sql_ins_topic = "insert into topics set topic     = '$private_topic',
																		 privateid = $privateid,
																		 global    = 'N'";
								if($result = mysql_query($sql_ins_topic))
									{
									$topicid = mysql_insert_id();
									$save_topic_image = "Y";
									}
								}
							}
						}
					
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
							$mypageExtract = $_SESSION['mypageExtract'];
							$imgURL = array();
							$imgData = array();
							$size = array();
							$imagethumb = array();
						//echo '<pre>';
						//print_r($_POST);
						//print_r($mypageExtract['images']);
						//echo '</pre>';
							
							$k = 1;
							$y = 0;
							for ($i=0; $i<3; $i++)
								{
								if(isset($mypageExtract['images'][$i]))
									{
									if($image == $k)
										{
										$imgURL[$y] = $mypageExtract['images'][$i];
										$imgData[$y] = file_get_contents($imgURL[$y]);
										if($save_topic_image == 'Y')
											{
											$topic_image = parse_url($imgURL[$y], PHP_URL_PATH);
											if(copy($imgData[$y], $folder . $topic_image ))
												{
												$sql = "update topics set topic_image  = '$url'
														where topicid = '$topicid' limit 1";
												$result = mysql_query($sql);
												}
											
											}
										if (!$imgData[$y])
											{
											echo '<br/>imgURL'.$imgURL[$y];
											die("Error: Invalid File Specified");
											}
										if($imgData[$y])
											{
											$size[$y] = getimagesize($imgURL[$y]);
											
											$imgData[$y] = mysql_real_escape_string($imgData[$y]);
											
											if ($size[$y]['mime'] == '') // getimagesize failed to determine the file type
												{
												// write file data to a temporary file so we can get file info, such as mimetype
												$fileData = file_get_contents($mypageExtract['images'][$i]);
												$tmp=array_search('uri', @array_flip(stream_get_meta_data($GLOBALS[mt_rand()]=tmpfile())));
												file_put_contents($tmp, $fileData);
											
												$finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
												$size[$y]['mime'] = finfo_file($finfo, $tmp) . "\n";
												finfo_close($finfo);
												}
											//now go get the scaled down IMAGE
											$width = 228;  //actual cell is 225x96 this will scale down nicely after cropping
											$height = 128;
											$crop = 1;
										//echo '<br/>imageurl['.$y.']: '.$imgURL[$y];
										//echo '<br/>imgData[i]: '.$imgData[$i];
										//echo '<br/>width: '.$width;
										//echo '<br/>height: '.$height;
											$imagethumb[$y] = image_resize($imgURL[$y], $imgData[$y], $width, $height, $crop);
										//echo '<br/>y: '.$y;
											//$imagethumb[$y] = $imgData[$i];
											$y++;
											}
										}
									}
								$k++;
								}
							//now save the image(s)
							
							//no sense saving 2 copies of full image until i figure out scaling...
							$imgData[0] = '';
							$imgData[1] = '';
							$imgData[2] = '';
							$imgData[3] = '';
							//***********
//							$sql = "INSERT INTO content_image ( image_id , image_type , image_size, image_name, contentid, image_url_orig, image, image_full,
//																image2, image2_full, image2_size, image3, image3_full, image3_size, image4, image4_full, image4_size)
//														VALUES ('', '{$size[0]['mime']}', '{$size[0][3]}', '{$imgURL[0]}', '$contentid', '$url', '{$imagethumb[0]}', '{$imgData[0]}',
//																'{$imagethumb[1]}', '{$imgData[1]}', '{$size[1][3]}', '{$imagethumb[2]}', '{$imgData[2]}', '{$size[2][3]}', '{$imagethumb[3]}', '{$imgData[3]}', '{$size[3][3]}' )";
							$sql = "INSERT INTO content_image ( image_id , image_type , image_size, image_name, contentid, image_url_orig, image, image_full)
												VALUES ('', '{$size[0]['mime']}', '{$size[0][3]}', '{$imgURL[0]}', '$contentid', '$url', '{$imagethumb[0]}', '{$imgData[0]}')";
							
							if(!$error = mysql_query($sql) and $userguid == '1004833642')
								{
								echo '<br/><br/>Error: '.mysql_error();
								echo '<br/><br/>533 SQL: '.$sql.'<br/>';
								}
								
							//now go get the article contents with READABILITY
							$magic_quotes = ini_get("magic_quotes_gpc") ? true : false;
							
							if($magic_quotes)
								$request_url = stripslashes($url);
							else
								$request_url = $url;

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
							if(isset($content) and $content != '' and $readit === false)
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
															tags      = '$tags2',
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
				}
			}

//**********************  create personal knowledge cells

		if(!empty($personal_topic) and $personal_topic != '')
			{
			$tagarray_load = explode(',',$personal_topic);
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
					//get the global topic image if it was set...
					$qu_global = "select topic_image from topics where topic = '$community_global' limit 1";
					$result = mysql_query($qu_global);
					$result2 = db_result_to_array($result);
					foreach($result2 as $rowimage)
						{
						$topic_image = $rowimage['topic_image'];
						}
					$community = stripslashes($community);
					$community = str_replace("'","",$community);
					$community = str_replace('"',"",$community);
					$qu_insert = "insert into topics set   topic       = '$community',
														   topic_image = '$topic_image' ";
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
																title    = '$title',
																userguid = '$userguid'
																";
						$result = mysql_query($qu_insert);
						$contentid = mysql_insert_id();
							
						//save the images for this content... up to 4 images save smaller images in image, image2, image3, image4
						//save full image in image_full, image2_full, image3_full, image4_full
						$mypageExtract = $_SESSION['mypageExtract'];
						$imgURL = array();
						$imgData = array();
						$size = array();
						$imagethumb = array();
						//echo '<pre>';
						//print_r($_POST);
						//print_r($mypageExtract['images']);
						//echo '</pre>';
						$y = 0;
						for ($i=0; $i<4; $i++)
							{
							if(isset($mypageExtract['images'][$i]))
								{
								if(isset($_POST['saveimage'.$i]))
									{
									$imgURL[$y] = $mypageExtract['images'][$i];
									$imgData[$y] = file_get_contents($imgURL[$y]);
									
									
									if (!$imgData[$y])
										{
										echo '<br/>imgURL'.$imgURL[$y];
										die("Error: Invalid File Specified");
										}
									if($imgData[$y])
										{
										$size[$y] = getimagesize($imgURL[$y]);
										
										$imgData[$y] = mysql_real_escape_string($imgData[$y]);
										
										if ($size[$y]['mime'] == '') // getimagesize failed to determine the file type
											{
											// write file data to a temporary file so we can get file info, such as mimetype
											$fileData = file_get_contents($mypageExtract['images'][$i]);
											$tmp=array_search('uri', @array_flip(stream_get_meta_data($GLOBALS[mt_rand()]=tmpfile())));
											file_put_contents($tmp, $fileData);
										
											$finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
											$size[$y]['mime'] = finfo_file($finfo, $tmp) . "\n";
											finfo_close($finfo);
											}
										//now go get the scaled down IMAGE
										$width = 228;  //actual cell is 225x96 this will scale down nicely after cropping
										$height = 128;
										$crop = 1;
									//echo '<br/>imageurl['.$y.']: '.$imgURL[$y];
									//echo '<br/>imgData[i]: '.$imgData[$i];
									//echo '<br/>width: '.$width;
									//echo '<br/>height: '.$height;
										$imagethumb[$y] = image_resize($imgURL[$y], $imgData[$y], $width, $height, $crop);
									//echo '<br/>y: '.$y;
										//$imagethumb[$y] = $imgData[$i];
										$y++;
										}
									}
								}
							}
						//now save the image(s)
										//no sense saving 2 copies of full image until i figure out scaling...
							$imgData[0] = '';
							$imgData[1] = '';
							$imgData[2] = '';
							$imgData[3] = '';
							//***********
						//	$sql = "INSERT INTO content_image ( image_id , image_type , image_size, image_name, contentid, image_url_orig, image, image_full,
						//									image2, image2_full, image2_size, image3, image3_full, image3_size, image4, image4_full, image4_size)
						//							VALUES ('', '{$size[0]['mime']}', '{$size[0][3]}', '{$imgURL[0]}', '$contentid', '$url', '{$imagethumb[0]}', '{$imgData[0]}',
						//									'{$imagethumb[1]}', '{$imgData[1]}', '{$size[1][3]}', '{$imagethumb[2]}', '{$imgData[2]}', '{$size[2][3]}', '{$imagethumb[3]}', '{$imgData[3]}', '{$size[3][3]}')";
							$sql = "INSERT INTO content_image ( image_id , image_type , image_size, image_name, contentid, image_url_orig, image, image_full)
														VALUES ('', '{$size[0]['mime']}', '{$size[0][3]}', '{$imgURL[0]}', '$contentid', '$url', '{$imagethumb[0]}', '{$imgData[0]}')";
						
						if(!$error = mysql_query($sql) and $userguid == '1004833642')
							{
							echo '<br/><br/>Error: '.mysql_error();
							echo '<br/><br/>533 SQL: '.$sql.'<br/>';
							}
							
								
							//now go get the article contents with READABILITY
							$magic_quotes = ini_get("magic_quotes_gpc") ? true : false;
							
							if($magic_quotes)
								$request_url = stripslashes($url);
							else
								$request_url = $url;

							$request_url_hash = md5($url);

							$handle = curl_init();
							curl_setopt_array($handle, array(
								CURLOPT_USERAGENT => USER_AGENT,
								CURLOPT_FOLLOWLOCATION => true,
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
							if(isset($content) and $content != '' and $readit === false and $contentid != 0)
								{
								//$contentto = mb_convert_encoding($content);
								$content = addslashes($content);
									
								$sql = "insert into article_page set contentid = '$contentid',
																	 page      = 1,
																	 body      = '$content',
																	 userguid  = '$userguid',
																	 date_created = '$dt' ";
								if(!$result = mysql_query($sql) and userguid == '1004833642')
									{
									echo '<br/><br/>Error: '.mysql_error();
									echo '<br/><br/>article SQL: '.$sql.'<br/>';
									}
								}
							
						}
						
					$mydate = date('Y-m-d G:i:s');
					$qu_insert = "insert into curations set userguid = '$userguid',
															topicid  = '$topicid',
															contentid = '$contentid',
															date_created = '$mydate',
															tags     = '$tags2'
															";
					$result = mysql_query($qu_insert);
						
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
				}
			}

//***********************
	
		//load view
		$nav_search_discover = 'jumppad.php';
		$currentDiscover='';
		$currentPersona='';
		$nav_search_discover = 'jumppad.php';
		
		include('../views/harvester_post_view.php');
		
		if($userguid == '1004833642')
			{
			echo '<br><br>only jmc:';
			echo '<pre>';
			print_r($_POST);
			echo '</pre>';
			echo '<br>server:'.SERVERPD;
			}

		}
?>
