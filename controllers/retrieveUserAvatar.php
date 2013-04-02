<?php

require_once('../libraries/initialize.php');

if (!isset($_GET['id'])) die("Error: Invalid Image ID");

$sql = "SELECT avatar, avatar_image_type FROM users WHERE userguid = " . $_GET['id'];

$result = mysql_query($sql)
	or die(mysql_error());

$row = mysql_fetch_assoc($result);

header("Content-type: " . $row['avatar_image_type']);
echo $row['avatar'];

