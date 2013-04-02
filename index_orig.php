<?php
	error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_startup_errors', 'On');
ini_set('display_errors', 'On');

//require_once('Smarty.class.php');
require_once('libraries/initialize.php');
$user = User::find_by_id($_SESSION['user_id']);

$users = User::find_by_sql('select * from users order by last_name, first_name');
?>

<html>
	<head>
		<title>Photo Gallery</title>
		<link href="controllers/css/main_gallery.css" media="all" rel="stylesheet" type="text/css" />
	</head>
	<body>
		<div id="header">
			<h1>Photo Gallery</h1>
		</div>
		<div id="main">
			<h2>Menu</h2>
			<p><?php echo $user->full_name(); ?></p>
			<?php foreach($users as $user)
					{
					echo '<p>Name: ' . $user->name . ' PW: '.$user->password.'</p>';
					}
			?>
		</div>	
		<div id="footer">Copyright <?php echo date("Y", time()); ?>, So:KNO Incorporated</div>
	</body>
</html>