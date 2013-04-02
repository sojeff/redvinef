<?php

require_once('../libraries/initialize.php');

if (!isset($_GET['id'])) die("Error: Invalid Image ID");

$sql = "SELECT image, image_type FROM content_image WHERE image_id=" . $_GET['id'];

$result = mysql_query($sql)
	or die(mysql_error());

$row = mysql_fetch_assoc($result);

header("Content-type: " . $row['image_type']);
echo $row['image'];

