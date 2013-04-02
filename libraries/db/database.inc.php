<?php


$_SESSION['dbname']  = "liveditions";
$_SESSION['dbhost']  = "localhost";
$_SESSION['dbuser']  = "ubuntu";
$_SESSION['dbpass']  = "lhcedh1234!";

function le_dbconnect()
{
	global $_SESSION;
    $dbname = $_SESSION['dbname'];    
    $dbhost = $_SESSION['dbhost'];    
    $dbuser = $_SESSION['dbuser'];    
    $dbpass = $_SESSION['dbpass'];    

    $result = mysql_connect($dbhost, $dbuser, $dbpass) or die(mysql_error());
    if($result)
    	{
    	$result = mysql_select_db($dbname) or die(mysql_error());
    	return $result;
    	}
    else 
    	return $result;
}

function updateFbUser($userInfo)
{
	global $_SESSION;
    // d($userInfo);

    $_SESSION['userguid'] = $userInfo['id'];

    le_dbconnect();

    $userguid   = $userInfo['id'];
    $name       = $userInfo['name'];
    $first_name = $userInfo['first_name'];
    $last_name  = $userInfo['last_name'];
    $link       = $userInfo['link'];
    $birthday   = $userInfo['birthday'];
    $gender     = $userInfo['gender'];
    $email      = $userInfo['email'];
    $timezone   = $userInfo['timezone'];
    $locale     = $userInfo['locale'];
    $verified   = $userInfo['verified'];

    $qu_insert = "insert into users set
        userguid   = $userguid,
        name       = '$name',
        first_name = '$first_name',
        last_name  = '$last_name',
        link       = '$link',
        birthday   = '$birthday',
        gender     = '$gender',
        email      = '$email',
        timezone   = '$timezone',
        locale     = '$locale',
        verified   = $verified
    ";
    
    // echo $qu_insert . "<br>";

    $result =  mysql_query($qu_insert) or die(mysql_error());
}

function db_result_to_array($result)
{
   $res_array = array();

   for ($count=0; $row = @mysql_fetch_array($result); $count++)
     $res_array[$count] = $row;

   return $res_array;
}

?>
