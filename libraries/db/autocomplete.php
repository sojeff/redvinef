<?php
session_start();

include_once $_SERVER['DOCUMENT_ROOT']."/lewebkit.php";
$return_data = false;

//echo '<br/>'.$_SERVER['DOCUMENT_ROOT']."/lewebkit.php";
//echo '<br/>POST: '.$_SERVER['DOCUMENT_ROOT']."/lewebkit.php";
// if the 'term' variable is not sent with the request, exit

if (isset($_REQUEST['term']))
	{
	$db_conn = false;
	$db_conn = le_dbconnect();
	if(!$db_conn)
		{
		echo '<br><h2>Unable to connect to database!';
		exit;
		}
	//get the $data for the saveit.php (bookmarklet)
	$getdata = mysql_real_escape_string($_REQUEST['term']);
	$data = array();	
	$return_data = true;
	$qu_topics = "select * from topics where global != 'Y' and topic like '$getdata%'";
	//$data[] = array('label' => $qu_topics, 'value' => $qu_topics);
	$result = mysql_query($qu_topics);
	$result2 = db_result_to_array($result);
	foreach($result2 as $rowtopics)
		{
		$topic = $rowtopics['topic'];
		$data[] = array('label' => $rowtopics['topic'], 'value' => $rowtopics['topic']);
		}
	
	}
// connect to the database server and select the appropriate database for use

 
// jQuery wants JSON data
//echo '<h1>Hello</h1>';

if($return_data)
	{
	echo json_encode($data);
	flush();
	}