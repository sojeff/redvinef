<?php
	error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_startup_errors', 'On');
ini_set('display_errors', 'On');

//require_once('Smarty.class.php');
require_once('../../libraries/initialize.php');

if($session->is_logged_in())
	{
	redirect_to("index.php");
	}

$message = "";
	
//remember to give your form's submit tag a name="submit" attribute!
if(isset($_POST['submit'])) 
	{
	//form has been subitted
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);
	
	//check database to see if username/password exists
	$found_user = User::authenticate($username, $password);
	
	if($found_user)
		{
		$session->login($found_user);
		log_action('Login', "{$found_user->email} logged in.");
		redirect_to("index.php");
		}
	else
		{
		//username/password combo was not found in the Database
		$message = "Username and/or password combination was incorrect.";
		}
	
	}
else
	{
	//form has not been submitted
	$username = "";
	$password = "";
	}

?>

<html>
	<head>
		<title>Photo Gallery</title>
		<link href="../../controllers/css/main_gallery.css" media="all" rel="stylesheet" type="text/css" />
	</head>
	<body>
		<div id="header">
			<h1>Photo Gallery</h1>
		</div>
		<div id="main">
			<h2>Staff Login</h2>
			<?php echo output_message($message); ?>
			<form action="login.php" method="post">
				<table>
					<tr>
						<td>Username:</td>
						<td>
							<input type="text" name="username" maxlength="30" value="<?php echo htmlentities($username); ?>" />
						</td>
					</tr>
					<tr>
						<td>Password:</td>
						<td>
							<input type="text" name="password" maxlength="30" value="<?php echo htmlentities($password); ?>" />
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<input type="submit" name="submit" value="Login" />
						</td>
					</tr>
				</table>
			</form>
						
		</div>	
		<div id="footer">Copyright <?php echo date("Y", time()); ?>, So:KNO Incorporated</div>
	</body>
</html>
<?php if(isset($database) and 1==2) { $database->close_connection(); } ?>