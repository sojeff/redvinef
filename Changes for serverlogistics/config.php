<?php
//config.php

if(strtolower($_SERVER['HTTP_HOST']) == 'getsokno.com')
	{
	// Database Constants
	defined('DB_SERVER') ? null : define("DB_SERVER", "localhost"); //if database is running on same server as apache
	defined('DB_USER') ? null : define("DB_USER", "getsokno");
	defined('DB_PASS') ? null : define("DB_PASS", "0iu3G6T1vs");
	defined('DB_NAME') ? null : define("DB_NAME", "getsokno_redvine");
	
	//email constants
	defined('SUPPORT_EMAIL_lvl1') ? null : define("SUPPORT_EMAIL_lvl1", "jeff@getsokno.com"); //level 1 support email
	defined('SUPPORT_EMAIL_lvl2') ? null : define("SUPPORT_EMAIL_lvl2", "kevin@getsokno.com"); //level 2 support email
	defined('SUPPORT_EMAIL_lvl3') ? null : define("SUPPORT_EMAIL_lvl3", "pete@getsokno.com"); //level 3 support email
	defined('MARKETING_EMAIL') ? null : define("MARKETING_EMAIL", "eric@getsokno.com"); //marketing email
	defined('SALES_EMAIL') ? null : define("SALES_EMAIL", "dan@getsokno.com"); //marketing email
	}
else
	{
	// Database Constants
	defined('DB_SERVER') ? null : define("DB_SERVER", "localhost"); //if database is running on same server as apache
	defined('DB_USER') ? null : define("DB_USER", "ubuntu");
	defined('DB_PASS') ? null : define("DB_PASS", "lhcedh1234!");
	defined('DB_NAME') ? null : define("DB_NAME", "liveditions");
	
	//email constants
	defined('SUPPORT_EMAIL_lvl1') ? null : define("SUPPORT_EMAIL_lvl1", "jeff@getsokno.com"); //level 1 support email
	defined('SUPPORT_EMAIL_lvl2') ? null : define("SUPPORT_EMAIL_lvl2", "kevin@getsokno.com"); //level 2 support email
	defined('SUPPORT_EMAIL_lvl3') ? null : define("SUPPORT_EMAIL_lvl3", "pete@getsokno.com"); //level 3 support email
	defined('MARKETING_EMAIL') ? null : define("MARKETING_EMAIL", "eric@getsokno.com"); //marketing email
	defined('SALES_EMAIL') ? null : define("SALES_EMAIL", "dan@getsokno.com"); //marketing email

	}

?>