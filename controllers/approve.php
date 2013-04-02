<?php
//session_start();
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_startup_errors', 'On');
ini_set('display_errors', 'On');

require_once('../libraries/initialize.php');

/*
echo '<pre>';
echo '<br>get<br>';
print_r($_GET);
echo '<br>post<br>';
print_r($_POST);
echo '</pre>';
*/ 

if(isset($_GET['sucode']) and $_GET['sucode'] != '' and isset($_GET['uname']) and $_GET['uname'] != '' or
   (isset($_POST['private_user_topics']) and !isset($_POST['approveuser'])))
	{
	$private_user_topics = '';
	$private_index_part = 0;
	if(isset($_GET['uname']))
		{
		$email = $_GET['uname'];
		$signup_code = $_GET['sucode'];
		}
	if(isset($_POST['private_user_topics']))
		{
		$email = $_POST['email'];
		$signup_code = $_POST['sucode'];
		$name = $_POST['name'];
		$userguid = $_POST['userguid'];
		$private_user_topics = $_POST['private_user_topics'];
		$private_index_part = strtok($private_user_topics,'||');
		$private_user_topics = strtok('||');
		echo '<br>private index: '.$private_index_part;
		}
	//echo 'okay';
/*	if(isset($_SESSION['private_user_topics']))
		$private_user_topics = $_SESSION['private_user_topics'];
	if(isset($_POST['private_user_topics']))
		{
		$_SESSION['private_user_topics'] = $_POST['private_user_topics'];
		$private_user_topics = $_SESSION['private_user_topics'];
		}
*/
	//check database to see if username/password exists
	$sql = "select * from users where email = '$email' and signup_code = '$signup_code' limit 1";
	$result = mysql_query($sql);
	$arr = db_result_to_array($result);
	foreach ($arr as $u)
		{
	
		$userguid = $u['userguid'];
		$name = $u['name'];
		$password = $u['password'];
		$first_name = $u['first_name'];
		$last_name = $u['last_name'];
		echo '<table>';
		echo '<tr>';
		echo '<td nowrap colspan="2">Validate User, add to private group (optional)</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>&nbsp;</td></tr>';
		echo '<tr>';
		echo '<td nowrap>Name</td><td nowrap>'.$name.'</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td nowrap>Email</td><td>'.$email.'</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td nowrap>User Guid</td><td>'.$userguid.'</td>';
		echo '</tr>';
		echo '<form action="approve.php" method="post">';
		echo '<input type="hidden" name="userguid" id="userguid" value="'.$userguid.'" />';
		echo '<input type="hidden" name="sucode" id="sucode" value="'.$signup_code.'" />';
		echo '<input type="hidden" name="email" id="email" value="'.$email.'" />';
		echo '<input type="hidden" name="name" id="name" value="'.$name.'" />';
		echo '<input type="hidden" name="private_index_part" id="private_index_part" value="'.$private_index_part.'" />';
		echo '<input type="hidden" name="private_cell_name" id="private_cell_name" value="'.$private_user_topics.'" />';
		echo '<input type="hidden" name="password" id="password" value="'.$password.'" />';
		echo '<input type="hidden" name="first_name" id="first_name" value="'.$first_name.'" />';
		echo '<input type="hidden" name="last_name" id="last_name" value="'.$last_name.'" />';
	
		echo '<tr>';

		echo '<td>Private JumpPad?</td><td><select name="private_user_topics" onchange="this.form.submit()">';
		echo '<option selected="'.$private_index_part.'||'.$private_user_topics.'">'.$private_user_topics.'</option>';
		$sqlpvt = "select * from private_user_topics";
		$resultpvt = mysql_query($sqlpvt);
		$arrpvt = db_result_to_array($resultpvt);
		foreach ($arrpvt as $pvt)
			{
			$private_cell_name = $pvt['private_cell_name'];
			$index_part = $pvt['index_part'];
			echo '<option value="'.$index_part.'||'.$private_cell_name.'">'.$private_cell_name.'</option>';
			}
		echo '</select>';
		echo '</td>';
		echo '</tr>';

		//now get the private member groups for this private user topic
		$sqlpvtgrp = "select * from private_groups where private_user_index_part = '$private_index_part' order by private_group_title";
		//echo '<br>sqlpvtgrp: '.$sqlpvtgrp;
		echo '<tr>';

		echo '<td>Private Group?</td><td><select name="private_groups">';
		echo '<option value=""></option>';
		$resultgrp = mysql_query($sqlpvtgrp);
		$arrgrp = db_result_to_array($resultgrp);
		foreach ($arrgrp as $grp)
			{
			$private_group_title = $grp['private_group_title'];
			$privateid = $grp['privateid'];
			echo '<option value="'.$privateid.'">'.$private_group_title.'</option>';
			}
		echo '</select>';
		echo '</td>';
		echo '</tr>';
		//set access level 
		echo '<tr>';
		echo '<td>User Access for Private</td><td><select name="access">';
		echo '<option value="user">user</option><option value="admin">admin</option>';
		echo '</select>';
		echo '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>Send Email?</td><td><select name="sendemail">';
		echo '<option value="Yes">Yes</option><option value="No">No</option>';
		echo '</select>';
		echo '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<tr>';
		echo '<td>&nbsp;</td></tr>';
		echo '<tr>';
		echo '<td>&nbsp;</td></tr>';
		echo '<td><input type="submit" name="approveuser" value="Submit for Approval" /></td>';
		echo '</tr>';
		echo '</form>';
		echo '</table>';

		}
	
	}
else if(isset($_POST['sucode']) and $_POST['sucode'] != '' and isset($_POST['userguid']) and $_POST['userguid'] != '')
	{
		$userguid = $_POST['userguid'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		$first_name = $_POST['first_name'];
		$last_name = $_POST['last_name'];
		$sendemail = $_POST['sendemail'];
		
		$private_cell_name = $_POST['private_cell_name'];
		$private_index_part = $_POST['private_index_part'];
		$can_create_private = 'N';
		if($private_cell_name != '')
			$can_create_private = 'Y';

		$privateid = $_POST['private_groups'];
		$access = $_POST['access'];

		$sqlu = "update users set verified = 1, 
								  private_cell_name = '$private_cell_name',
								  can_create_private = '$can_create_private'
							where userguid = '$userguid' limit 1";
	//echo '<br>sqlu: '.$sqlu;
		$result = mysql_query($sqlu);
		if($result)
			{
			echo '<br><br>SAVED!!!';
			if($private_cell_name != '' and $privateid > 0)
				{
				$sqlpvtmemgrp = "insert into private_groups_members set private_user_index_part = '$private_index_part',
																		userguid = '$userguid',
																		privateid = '$privateid',
																		access    = '$access' ";
				$result = mysql_query($sqlpvtmemgrp);
				//echo '<br>'.$sqlpvtmemgrp;

				}
//send a welcome email to new user - approved
			if($sendemail == 'Yes')
				{
				$from = "support@getsokno.com";
				$server = whichserver();
				if($server == 'Development')
					$subject = "TEST ONLY - Welcome to SO:KNO, Your Account has been activated";
				else
					$subject = "Welcome to SO:KNO, Your Account has been activated";
				$body = "
<html> 
  <body bgcolor=\"#EEEEEE\"> 
<p>A new user account has been created at </p>

<p><a href=\"https://getsokno.com\">https://getsokno.com</a></p>

<p>Login: $email</p>
<p>Password: $password</p>

<p>Welcome to SO:KNO!  Thanks for making SO:KNO your place to find and share knowledge. We're glad to have you with us.</p>
<p>Please login with your email and password, and update your password at your earliest convenience using the Settings button. Feel free to add any additional personal information as desired. </p>
<p>After logging in, please navigate to the <a href=\"https://getsokno.com/redvinef/controllers/about.php?in=3\">About/Tools page</a> and install the SO:KNO Harvester onto your browser. SO:KNO'S Harvest tool lets you add content you care about and share what you find with others. Once installed in your browser, the Harvest button lets you grab articles, webpages, videos, pictures from any website and add it to your Cells. </p>
<p>There are Training Videos to assist you in speeding up your skills on SO:KNO. They are located in > About Us >  Tools.
<p>Once you've logged in you are now ready to connect to JumpPads where you will find tiles of \"Knowledge Cells\". These Knowledge Cells are a mosaic composition of Content, Community and Collaboration living together harmoniously around a topic that has been created by you or another like-minded individual. You can engage by adding or curating content and joining in Knowledge Cell discussions or create new ones.</p>
<p>To join a Knowledge Cell, you must curate content, a comment or a tag to have your tile with your picture appear in that Knowledge Cell.</p>
<p>&nbsp;</p>
<p>If you have any questions, comments or suggestions, just send an email to support@getsokno.com</p>
<p>GET SOME KNOWLEDGE</p>
<p>SO:KNO Core!</p>
<p><strong>Eric Kmiec</strong> | CEO | SO:KNO</p>
<br>
<table border=0>
<tr><td nowrap>Name: </td><td nowrap>$first_name $last_name</td></tr>
<tr><td nowrap>Email: </td><td nowrap>$email</td></tr>
</table>
<br>
<br>
  </body>
</html>
";
				if($server == 'Production')
					{
					send_email($from, $email, $subject, $body);
					}
				send_email($from, 'kevin@getsokno.com, eric@getsokno.com, dan@getsokno.com, jeff@getsokno.com, dorietz@me.com', $subject, $body);
				}
			}
		else
			{
			echo '<br>not saved! '.$sql;
			echo '<br>'.$sqlu;
			}
	}
else
	echo "<br>Bad code entered!<br><br>Signup NOT approved!";
 ?>
 