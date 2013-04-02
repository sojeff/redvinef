<?php

	error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_startup_errors', 'On');
ini_set('display_errors', 'On');

  session_start();
    include_once "lewebkit.php";

	$db_conn = false;
	$db_conn = le_dbconnect();
	if(!$db_conn)
		{
		echo '<br><h2>Unable to connect to database!';
		exit;
		}

if(isset($_POST['userguid']))
{
	$userguid = $_POST['userguid'];
	$archive = 'redvinef/controllers/img/avatar_70x70/'.$userguid.'.jpg';

 if ($_FILES["file_url"]["error"] > 0)
    {
    	echo "Return Code: " . $_FILES["file_url"]["error"] . "<br />";
    }
  else
    {
	if(isset($_FILES["file_url"]["tmp_name"]))
	  {

	  move_uploaded_file($_FILES["file_url"]["tmp_name"], $archive);
			
	  $archive2 = 'redvinef/controllers/img/avatar_32x32/'.$userguid.'.jpg';
		if(isset($_FILES["file_url2"]["tmp_name"]))
		  {
		  move_uploaded_file($_FILES["file_url2"]["tmp_name"], $archive2);
		  echo '<br />Upload Successful. Next file to upload<br/><br/>';
		  }
		  
		$imgData = file_get_contents($archive);
		
		if (!$imgData)
			die("Error: Invalid File Specified");
				
		$imgData = mysql_real_escape_string($imgData);
				
		
		$sql = "update users set avatar = '{$imgData}', avatar_image_type = 'image/jpeg' where userguid = '$userguid' limit 1 ";
		
		mysql_query($sql);



	  }
    }
 }

if((isset($_GET['pword']) and $_GET['pword'] == '334455') or isset($_POST['userguid']) or isset($_SESSION['cleared']))
	{
	$_SESSION['cleared'] = 'clear';
	$sql = "select * from users order by name";
	$resulttop = mysql_query($sql);
	$resulttop2 = db_result_to_array($resulttop);
	
	echo '<form action="file_upload_avatar.php" method="post" enctype="multipart/form-data" >';
	echo '<br/><br/><br/>Save Avatar to User ID: ';
	echo '<select name="userguid">';
	foreach($resulttop2 as $rowtop)
		{
		$userguid = $rowtop['userguid'];
		$name = $rowtop['name'];
		$email = $rowtop['email'];
		$path = 'redvinef/controllers/img/avatar_70x70/'.$userguid.'.jpg';
		//if(!file_exists($path))
			echo '<option value="'.$userguid.'">'.$name.'-'.$email.'</option>';
		}
	echo '</select>';
	echo '<br/><label for="file">70x70 Filename:</label>';
	echo '<input type="file" name="file_url" id="file_url" />';
	echo '<br/><label for="file2">32x32 Filename:</label>';
	echo '<input type="file" name="file_url2" id="file_url2" />';
	echo '<br /><br />';
	echo '<input type="submit" name="submit" value="Upload" />';
	echo '</form>';
	}
else
	echo '<br /><b>UNAUTHORIZED access...';
?> 

