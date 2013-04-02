<?php
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_startup_errors', 'On');
ini_set('display_errors', 'On');

ini_set( 'max_execution_time', '7200' );

require_once('../libraries/initialize.php');


$error = "";
$linect = 0;

if(isset($_GET['serv']) and $_GET['serv'] == 'prod')
	$server = 'prod';
else if(isset($_GET['serv']) and $_GET['serv'] == 'test')
	$server = 'test';
else
	{
	echo '<br/>Get var serv not set...';
	exit;
	}

$filename = "../userdata/hartnell_students.csv";
	
$results = file ($filename);
# process the file
echo '<br/>Count Results: '.count($results);

		
		$linect = 0;
		$counter = 0;
		$added = 0;
		$address = '';
		$costcenter = '';
		$homesite = '';
		$istbd1 = 0;
		$istbd2 = 0;
		# open the file into an array
		foreach ($results as $line)
			{
			$linect++;
			$lineArr=explode(",",$line);
			# get the values from the file
			$topicid = str_replace('"', '', trim($lineArr[0]));
			$lastname = str_replace('"', '', trim($lineArr[1]));
			$firstname = str_replace('"', '', trim($lineArr[2]));
			$username = strtolower(str_replace('"', '', trim($lineArr[3])));
			$foreign_key = strtolower(str_replace('"', '', trim($lineArr[5])));
			$name = $firstname . ' ' . $lastname;
			
			$userguid = 0;
			
			echo "<br>'$topicid' '$lastname' '$firstname' '$username' '$foreign_key'";
			$password = '';
			
			$private_cell_name = 'Hartnell';
			$wherefromcity = 'Salinas';
			$wherefromstate = 'CA';
			$wherefromcountry = 'USA';
			$currentlocationcity = 'Salinas';
			$currentlocationstate = 'CA';
			$currentlocationcountry = 'USA';
			
			$verified = 1;
			$can_create_private = 'N';
			$user_status = 'active';
			
			//check database to see if username/password exists
			$sql = "select * from users where email = '$username' limit 1";
			//$found_user = User::find_by_sql($sql);
			
			$found_it = mysql_query($sql);
			$found_user = db_result_to_array($found_it);
			foreach($found_user as $foundu)
				{
				$userguid = $foundu['userguid'];
				$password = $foundu['password'];
				}
		
			if($userguid == 0)
				{
				//create the user	
				$dt = date('Y-m-d H:i:s');
				$newuser = new User;
				$newuser->first_name = $firstname;
				$newuser->last_name  = $lastname;
				$newuser->password   = strtolower(generateRandomString(6));
				$newuser->email      = $username;
				$newuser->user_status     = $user_status;
				$newuser->name       = $firstname.' '.$lastname;
				$newuser->verified   = 1;
				$newuser->updated_time = $dt;
				$newuser->private_cell_name = $private_cell_name;
				$newuser->wherefromcity = $wherefromcity;
				$newuser->wherefromstate = $wherefromstate;
				$newuser->wherefromcountry = $wherefromcountry;
				$newuser->currentlocationcity = $currentlocationcity;
				$newuser->currentlocationstate = $currentlocationstate;
				$newuser->currentlocationcountry = $currentlocationcountry;
				$newuser->foreign_key  = $foreign_key;
				$newuser->save();
			
				//check database to see if username/password exists
				$found_user = User::authenticate($username, $password, 1);
				
				$userguid = $newuser->userguid;
				$password = $newuser->password;
				}
				
			if(isset($userguid) and $userguid != 0)
				{
				echo ' - inside ';
				// now set up the private cell
				$private_user_index_part = '1000';
				
				$found_topic = topic::find_by_id($topicid);
				$privateid = $found_topic->privateid;
				
				if($privateid != 0)
					{
					$sqlpriv = "insert into private_groups_members set userguid = $userguid, 
																	   private_user_index_part = '$private_user_index_part',
																	   privateid = '$privateid', 
																	   access    = 'user' ";
					if(!$result = mysql_query($sqlpriv))
						echo '<br>error ';
						
					echo '<br>Sql: '.$sqlpriv;
					
					$from = "support@getsokno.com";
					if($server == 'test')
						$subject = "TEST ONLY - So Kno Create Account";
					else
						$subject = "So Kno Create Account";
					$body = "
<html> 
  <body bgcolor=\"#DDDDDD\"> 
<p>A new user account has been created at </p>

<p><a href=\"https://getsokno.com\">https://getsokno.com</a></p>

<p>using Email: $username</p>
<p>Password: $password</p>

<p>Welcome to SO:KNO!  Thanks for making SO:KNO your place to find and share knowledge. We're glad to have you with us.</p>
<p>Please login with your email and password, and update your password at your earliest convenience using the Settings button. Feel free to add any additional personal information as desired. </p>
<p>After logging in, please navigate to the <a href=\"https://getsokno.com/redvinef/controllers/about.php?in=3\">About/Tools page</a> and install the SO:KNO Harvester onto your browser. SO:KNO'S Harvest tool lets you add content you care about and share what you find with others. Once installed in your browser, the Harvest button lets you grab articles, webpages, videos, pictures from any website and add it to your Cells. </p>
<p>There are Training Videos to assist you in speeding up your skills on SO:KNO. They are located in > About Us >  Tools.
<p>Once you've logged in you are now ready to connect with other Hartnell students in your classes.  There's a place called \"Hartnell\" where you will find tiles of \"Knowledge Cells\" that represents each of your classes. Knowledge Cells are a mosaic composition of Content, Community and Collaboration living together harmoniously around a topic that have been created by your teacher and other classmates. You can engage by adding or curating content and joining in Knowledge Cell discussions or create new ones.</p>
<p>To join a Knowledge Cell, you must curate content, a comment or a tag to have your tile with your picture appear in that Knowledge Cell.</p>
<p>&nbsp;</p>
<p>If you have any questions, comments or suggestions, just send an email to support@getsokno.com</p>
<p>GET SOME KNOWLEDGE</p>
<p>SO:KNO Core!</p>
<p><strong>Eric Kmiec</strong> | CEO | SO:KNO</p>
<br>
<table border=0>
<tr><td nowrap>Name: </td><td nowrap>$firstname $lastname</td></tr>
<tr><td nowrap>Email: </td><td nowrap>$username</td></tr>
</table>
<br>
<br>
  </body>
</html>
";
					if($server == 'prod')
						{
						send_email($from, $username, $subject, $body);
						}
					send_email($from, 'eric@getsokno.com, dan@getsokno.com, jeff@getsokno.com, dorietz@me.com', $subject, $body);
					
					}
					
				}
				
			}

echo '<br/><br/>Total Records Updated: '.$linect;
?>