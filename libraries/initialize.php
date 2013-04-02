<?php

//define the core paths
//define them as absolute paths to make sure that require_once works as expected

//directory_separator is a php pre-defined constant
// (\ for windows, and / for unix)

defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

//************************************************************************
//test environment? change to blank before deploying to production.
defined('TEST_ENV') ? null : define("TEST_ENV", "/redvinef"); //comment for prod
//defined('TEST_ENV') ? null : define("TEST_ENV", "/demo"); //comment for prod
defined('DEMO_ENV') ? null : define("DEMO_ENV", "/demo"); //comment for prod

//defined('TEST_ENV') ? null : define("TEST_ENV", "");  //uncomment for prod
//************************************************************************

//if(strtolower($_SERVER['HTTP_HOST']) == 'getsokno.com')
//	defined('SITE_ROOT') ? null : define('SITE_ROOT', '/home/getsokno/domains/getsokno.com/public_html'.TEST_ENV);
//else

defined('SITE_ROOT') ? null : define('SITE_ROOT', $_SERVER['DOCUMENT_ROOT'].TEST_ENV);
	
defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.DS.'libraries');
defined('VIEWS_PATH') ? null : define('VIEWS_PATH', SITE_ROOT.DS.'views');
defined('CONTROLLERS_PATH') ? null : define('CONTROLLERS_PATH', SITE_ROOT.DS.'controllers');
defined('MODELS_PATH') ? null : define('MODELS_PATH', SITE_ROOT.DS.'models');

//echo '<br/>doc root: '.$_SERVER['DOCUMENT_ROOT'];
//echo '<br/>Site Root'. SITE_ROOT;
//echo '<br/>Host: '.strtolower($_SERVER['HTTP_HOST']);
//echo '<br/>address: '.strtolower($_SERVER['SERVER_ADDR']);
//echo '<br/>name: '.strtolower($_SERVER['SERVER_NAME']);

//load config file first
require_once(LIB_PATH.DS.'config.php');

require_once(LIB_PATH.DS.'functions.php');

//load core objects
require_once(LIB_PATH.DS.'session.php');
require_once(LIB_PATH.DS.'pagination.php');
require_once(LIB_PATH.DS.'image_resize_fns.php');
require_once(LIB_PATH.DS.'AutoEmbed.class.php');
require_once(LIB_PATH.DS.'php/config.inc.php');
require_once(LIB_PATH.DS.'php/lib/Readability.inc.php');

//load database class files
require_once(LIB_PATH.DS.'db/database.php');
require_once(LIB_PATH.DS.'db/database_object.php');
require_once(LIB_PATH.DS.'db/user.php');
require_once(LIB_PATH.DS.'db/photograph.php');
require_once(LIB_PATH.DS.'db/comment.php');
require_once(LIB_PATH.DS.'db/jumppad.php');
require_once(LIB_PATH.DS.'db/topic.php');
require_once(LIB_PATH.DS.'db/cell.php');
require_once(LIB_PATH.DS.'db/user_likes_flags.php');
require_once(LIB_PATH.DS.'db/user_levels.php');
require_once(LIB_PATH.DS.'db/user_follows.php');
require_once(LIB_PATH.DS.'db/article_page.php');
require_once(LIB_PATH.DS.'db/userviews.php');
require_once(LIB_PATH.DS.'db/edit_private.php');
require_once(LIB_PATH.DS.'db/private_user_topics.php');
require_once(LIB_PATH.DS.'db/private_groups.php');
require_once(LIB_PATH.DS.'db/private_groups_members.php');

date_default_timezone_set('America/Los_Angeles');

?>