<?php
//logfile.php in the admin/ folder
require_once("../../libraries/initialize.php");

	if(!session->is_logged_in())
		{
		redirect_to("login.php");
		}
	
	$logfile = '../logs/log.txt';
	
	if($_GET['clear'] == 'true')
		{
		file_put_contents($logfile, '');
		//add the 1st log entry
		log_action('Logs Cleared', "by User ID: {$session->user_id}");
		//redirect so URL wont have get var
		redirect_to('logfile.php');
		}
		
	include_layout_template('admin_header.php');
	
	?>
	
	<a href="index.php">%laquo; Back</a><br />
	<br />
	
	<h2>Log File</h2>
	
	<p><a href="logfile.php?clear=true">Clear Log file</a></p>
	
	<?php
	
	if(file_exists($logfile) && is_readable($logfile) && $handle = fopen($logfile, 'r'))
		{
		echo "<ul class=\"log-entries\">";
		while(!feof($handle))
			{
			$entry = fgets($handle);
			if(trim($entry) != "")
				{
				echo "<li>{$entry}</li>";
				}
			}
		echo "</ul>";
		fclose($handle);
		}
	else
		{
		echo "<br />Could not read from {$logfile}.";
		}

?>