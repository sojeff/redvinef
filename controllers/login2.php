<?php
	error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_startup_errors', 'On');
ini_set('display_errors', 'On');

//require_once('Smarty.class.php');
require_once('../libraries/initialize.php');

if($session->is_logged_in())
	{
	redirect_to("../index.php");
	}

/*
if(isset($_POST))
	{
	echo '<pre>';
	print_r($_POST);
	echo '</pre>';
	}
*/

//remember to give your form's submit tag a name="submit" attribute!
if(isset($_POST['login-button'])) 
	{
	//form has been subitted
	$username = trim($_POST['useremail']);
	$password = trim($_POST['passweird2']);
	
	//check database to see if username/password exists
	$found_user = User::authenticate($username, $password, 1);

	if($found_user)
		{
		$session->login($found_user);
		log_action('Login', "{$found_user->email} : logged in.");
		
		redirect_to("../index.php");
		}
	else
		{
		//username/password combo was not found in the Database
		$session->message = "Username and/or password combination was incorrect.";
		//login view
		
		
		include (VIEWS_PATH.DS.'login.php');

		}
	
	}
else if(isset($_POST['useremail']) and 
	    isset($_POST['useremail_confirm']) and
			  $_POST['firstname'] != 'First Name' and 
			  $_POST['firstname'] != '' and 
			  $_POST['lastname'] != 'Last Name' and 
			  $_POST['lastname'] != '' and
			  $_POST['useremail'] != '' and
			  $_POST['useremail'] == $_POST['useremail_confirm'] and 
			  isValidEmail($_POST['useremail'])) 
	{
	//form has been subitted
	
	$username = trim($_POST['useremail']);
	
	$password = trim($_POST['passweird2']);
	$firstname = ucwords(trim($_POST['firstname']));
	$lastname = ucwords(trim($_POST['lastname']));
	$gender = trim($_POST['gender']);
	
	//check database to see if username/password exists
	$sql = "select * from users where email = '$username' limit 1";
	$found_user = User::find_by_sql($sql);

	if(!$found_user)
		{
		//create the user	
		$dt = date('Y-m-d H:i:s');
		$newuser = new User;
		$newuser->first_name = $firstname;
		$newuser->last_name  = $lastname;
		$newuser->password   = $password;
		$newuser->email      = $username;
		$newuser->gender     = $gender;
		$newuser->name       = $firstname.' '.$lastname;
		$newuser->verified   = 0;
		$newuser->updated_time = $dt;
		$newuser->signup_code  = generateRandomString(6);
		$newuser->last_login = $dt;
		$newuser->login_count = 1;
		$newuser->login_fails_since_succ = 0;
		$newuser->save();
	
		//check database to see if username/password exists
		$found_user = User::authenticate($username, $password, 0);
		
	  	$server_name = $_SERVER['SERVER_NAME'];
	   if(SERVERPD == 'Prod')
			$linktoapprove2 = 'https://'.$_SERVER['SERVER_NAME'] .DS. 'redvinef' .DS. 'controllers'.DS. 'approve.php?uname='.$newuser->email.'&sucode='.$newuser->signup_code;
	   else
			$linktoapprove2 = 'http://'.$_SERVER['SERVER_NAME'] .DS. 'redvinef' .DS. 'controllers'.DS. 'approve.php?uname='.$newuser->email.'&sucode='.$newuser->signup_code;
		//$linktoapprove = "/controllers/approve.php?uname=".$newuser->email."&sucode=".$newuser->signup_code;
		if($found_user)
			{
			//$session->login($found_user);
			//log_action('Login', "{$found_user->email} : logged in.");
			$from = "sales@getsokno.com";
			$subject = "So Kno Create Account Request";
			if(SERVERPD != 'Prod')
				{
				$subject = $subject . ' Development server only';
				}
				
			$body = "
<html> 
  <body bgcolor=\"#EEEEEE\"> 
<p>A new user has created an account at $server_name using: $username.</p>
<p>For security reasons the password is not displayed.</p>
<br>
<br>
<table border=0>
<tr><td nowrap>Name: </td><td nowrap>$firstname</td><td nowrap>$lastname</td></tr>
<tr><td nowrap>Email: </td><td nowrap>$username</td></tr>
</table>
<br>
<br>
<p>Link to approve: <a href=\"$linktoapprove2\">Click to Approve this users access to SO:KNO</a></p>
  </body>
</html>
";
	  		if(SERVERPD == 'Prod')
				{
				send_email($from, 'eric@getsokno.com', $subject, $body);
				send_email($from, 'dan@getsokno.com', $subject, $body);
				send_email($from, 'dorietz@me.com', $subject, $body);
				}
			send_email($from, 'jeff@getsokno.com', $subject, $body);
			
			// redirect_to("./sokno_brain.php"); //no longer auto create an account and give access...
			// https://getsokno.com/redvinef/controllers/about.php?in=3
			
			include (VIEWS_PATH.DS.'waitlisted.php');
			}
		}
	else
		{
		//username/password combo was not found in the Database
		$session->message = "Username already exists.";
		alert("The email address (".$username.") has an account already.");
		//login view
		
		
		include (VIEWS_PATH.DS.'login.php');

		}
	
	}
else if(isset($_POST['forgot_login']) and $_POST['useremail'] != '')
	{
	$username = trim($_POST['useremail']);
	//check database to see if username/password exists
	$sql = "select * from users where email = '$username' limit 1";
	$found_user = User::find_by_sql($sql);

	if($found_user)
		{
		foreach($found_user as $fuser)
			{
			$pw = $fuser->password;
			}
		$from = "support@getsokno.com";
		$subject = "So Kno Password Request";
		$body = "
<html> 
  <body bgcolor=\"#DCEEFC\"> 
    <center> 
<p>A password was requested from this email address.</p>
<p>For security reasons the user id is not displayed.</p>
<br/>
<br/>
<p>Your current password: <strong>".$pw."</strong></p>
    </center> 
  </body>
</html>
";
		send_email($from, $username, $subject, $body);
		alert("Your password was sent to the email address: ".$username.". Please check your SPAM Folder as well.");
		
		include (VIEWS_PATH.DS.'login.php');
		}
	else
		{
		alert("The email address (".$username.") could not be found.");
		include (VIEWS_PATH.DS.'login.php');
		}
	}
else
	{
	//form has not been submitted
	$username = "";
	$password = "";
	//login view
	
	
	include (VIEWS_PATH.DS.'login.php');

	}

?>

<?php if(isset($database)) { $database->close_connection(); } ?>