<?php

function generateRandomString($length = 20) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

function db_result_to_array($result)
{
   $res_array = array();

   for ($count=0; $row = @mysql_fetch_array($result); $count++)
     $res_array[$count] = $row;

   return $res_array;
}

function strip_zeros_from_date( $marked_string = "")
	{
	//first remove the marked zeros
	$no_zeros = str_replace('*0', '', $marked_string);
	//then remove any remaining Marks
	$cleaned_string = str_replace('*', '', $no_zeros);
	return $cleaned_string;
	}
	
function redirect_to( $location = NULL)
	{
	if($location != NULL)
		{
		header("Location: {$location}");
		exit;
		}
	}

function output_message($message = "")
	{
	if(!empty($message))
		{
		return "<p class=\"message\">{$message}</p>";
		}
	else
		return "";
	}

function __autoload($class_name)
	{
	$class_name = strtolower($class_name);
	$path = LIB_PATH.DS."{$class_name}.php";
	if(file_exists($path))
		{
		require_once($path);
		}
	else
		{
		$path = LIB_PATH."db".DS."{$class_name}.php";
		}
		if(file_exists($path))
			{
			require_once($path);
			}
		else
			die("The file {$class_name}.php could not be found.");
	}

function include_layout_template($template="")
	{
	include(SITE_ROOT.DS.'views'.DS.$template);
	}

function log_action($action, $message="")
	{
	$logfile = SITE_ROOT.DS.'logs'.DS.'log.txt';
	$new = file_exists($logfile) ? false : true;
	if($handle = fopen($logfile, 'a')) 
		{
		//append
		$timestamp = strftime("%Y-%m-%d %H:%M:%S", time());
		$content = "{$timestamp} | {$action}: {$message}\n";
		fwrite($handle, $content);
		fclose($handle);
		if($new) 
			{
			chmod($logfile, 0755);
			}
		else
			{
			echo "Could not open log file for writing.";
			}
		}
	}
	
function datetime_to_text($datetime="") {
  $unixdatetime = strtotime($datetime);
  return strftime("%B %d, %Y at %I:%M %p", $unixdatetime);
  }
  
function content_age($created) {
  date_default_timezone_set('America/Los_Angeles');
  $elapsed = time() - strtotime($created);
  if($elapsed < 0)
  		$elapsed = $elapsed + 8*60*60; // quick fix for wrong timezone
  
  return strftime("%B %d, %Y at %I:%M %p", $return);
}

function diff_times($created)
	{
	date_default_timezone_set('America/Los_Angeles');
   	$start_time_for_conversion = strtotime($created);
    $end_time_for_conversion = time();
    
    $difference_of_times = $end_time_for_conversion - $start_time_for_conversion;
    if($difference_of_times < 0)
    	$difference_of_times = $difference_of_times + 8*60*60;
    
    $time_difference_string = "";
    
    for($i_make_time = 6; $i_make_time > 0; $i_make_time--)
    {
        switch($i_make_time)
        {
                // Handle Minutes
                // ........................
                
            case '1';
                $unit_title = "Min";
                $unit_size = 60;
                break;
                
                // Handle Hours
                // ........................
                
            case '2';
                $unit_title = "Hr";
                $unit_size = 3600;
                break;
                
                // Handle Days
                // ........................
                
            case '3';
                $unit_title = "Day";
                $unit_size = 86400;
                break;
                
                // Handle Weeks
                // ........................
                
            case '4';
                $unit_title = "Week";
                $unit_size = 604800;
                break;
                
                // Handle Months (31 Days)
                // ........................
                
            case '5';
                $unit_title = "Mon";
                $unit_size = 2678400;
                break;
                
                // Handle Years (365 Days)
                // ........................
                
            case '6';
                $unit_title = "Year";
                $unit_size = 31536000;
                break;
        }
    
        if($difference_of_times > ($unit_size - 1))
        {
            $modulus_for_time_difference = $difference_of_times % $unit_size;
            $seconds_for_current_unit = $difference_of_times - $modulus_for_time_difference;
            $units_calculated = $seconds_for_current_unit / $unit_size;
            $difference_of_times = $modulus_for_time_difference;
    		
    		if($units_calculated > 0)
    			{
            	$time_difference_string .= "$units_calculated $unit_title";
            	return $time_difference_string;
            	}
        }
    }
    
        // Handle Seconds
        // ........................
    
    $time_difference_string .= "$difference_of_times Sec";
	return $time_difference_string;
	
	}

function calculate_level ($eliteuser, $elite)
	{
	if($elite == false)
		{
		$level = 1;
		$stage = 1;
		$phaselevel = 'Newbie';
		$level_array = array($level, $stage, $phaselevel);
		return $level_array;
		}
	$eliteconsumertotals = $elite->pages_viewed + $elite->likes_count + $elite->curations_count + $elite->followed_count + $elite->following_count;
	$level = 1;
	$stage = 1;
	$phaselevel = 'Newbie';
	$nextlevel = '';
	
	if($eliteconsumertotals >= 0 and $eliteconsumertotals <= 50)
		{
		$level = 1; 
		$stage = 1;
		$phaselevel = 'Newbie';
		}
	else if($eliteconsumertotals > 50 and $eliteconsumertotals <= 75)
		{
		$level = 2; 
		$stage = 1;
		$phaselevel = 'Newbie';
		}
	else if($eliteconsumertotals > 75 and $eliteconsumertotals <= 100)
		{
		$level = 3; 
		$stage = 1;
		$phaselevel = 'Newbie';
		}
	else if($eliteconsumertotals > 100 and $eliteconsumertotals <= 150)
		{
		$level = 1; 
		$stage = 1;
		$phaselevel = 'Recruit';
		}
	else if($eliteconsumertotals > 150 and $eliteconsumertotals <= 250)
		{
		$level = 2; 
		$stage = 1;
		$phaselevel = 'Recruit';
		}
	else if($eliteconsumertotals > 250 and $eliteconsumertotals <= 350)
		{
		$level = 3; 
		$stage = 1;
		$phaselevel = 'Recruit';
		}
	else if($eliteconsumertotals > 350 and $eliteconsumertotals <= 500)
		{
		$level = 1; 
		$stage = 1;
		$phaselevel = 'Apprentice';
		}
	else if($eliteconsumertotals > 500 and $eliteconsumertotals <= 700)
		{
		$level = 2; 
		$stage = 1;
		$phaselevel = 'Apprentice';
		}
	else if($eliteconsumertotals > 700 and $eliteconsumertotals <= 900)
		{
		$level = 3; 
		$stage = 1;
		$phaselevel = 'Apprentice';
		}
	else if($eliteconsumertotals > 900 and $eliteconsumertotals <= 1200)
		{
		$level = 1; 
		$stage = 1;
		$phaselevel = 'Protégé';
		}
	else if($eliteconsumertotals > 1200 and $eliteconsumertotals <= 1500)
		{
		$level = 2; 
		$stage = 1;
		$phaselevel = 'Protégé';
		}
	else if($eliteconsumertotals > 1500 and $eliteconsumertotals <= 2800)
		{
		$level = 3; 
		$stage = 1;
		$phaselevel = 'Protégé';
		}
	else if($eliteconsumertotals > 2800 and $eliteconsumertotals <= 3000)
		{
		$level = 1; 
		$stage = 2;
		$phaselevel = 'Warrior';
		}
	else if($eliteconsumertotals > 3000 and $eliteconsumertotals <= 3200)
		{
		$level = 2; 
		$stage = 2;
		$phaselevel = 'Warrior';
		}
	else if($eliteconsumertotals > 3200 and $eliteconsumertotals <= 3400)
		{
		$level = 3; 
		$stage = 2;
		$phaselevel = 'Warrior';
		}
	else if($eliteconsumertotals > 3400 and $eliteconsumertotals <= 3500)
		{
		$level = 1; 
		$stage = 2;
		$phaselevel = 'Samurai';
		}
	else if($eliteconsumertotals > 3500 and $eliteconsumertotals <= 4000)
		{
		$level = 2; 
		$stage = 2;
		$phaselevel = 'Samurai';
		}
	else if($eliteconsumertotals > 4000 and $eliteconsumertotals <= 5000)
		{
		$level = 3; 
		$stage = 2;
		$phaselevel = 'Samurai';
		}
	else if($eliteconsumertotals > 5000 and $eliteconsumertotals <= 5500)
		{
		$level = 1; 
		$stage = 2;
		$phaselevel = 'Ninja';
		}
	else if($eliteconsumertotals > 5500 and $eliteconsumertotals <= 6000)
		{
		$level = 2; 
		$stage = 2;
		$phaselevel = 'Ninja';
		}
	else if($eliteconsumertotals > 6500 and $eliteconsumertotals <= 7000)
		{
		$level = 3; 
		$stage = 2;
		$phaselevel = 'Ninja';
		}
	else if($eliteconsumertotals > 7000 and $eliteconsumertotals <= 7500)
		{
		$level = 1; 
		$stage = 2;
		$phaselevel = 'Knight';
		}
	else if($eliteconsumertotals > 7500 and $eliteconsumertotals <= 8000)
		{
		$level = 2; 
		$stage = 2;
		$phaselevel = 'Knight';
		}
	else if($eliteconsumertotals > 8000 and $eliteconsumertotals <= 8500)
		{
		$level = 3; 
		$stage = 2;
		$phaselevel = 'Knight';
		}
	else if($eliteconsumertotals >= 8500 and $eliteconsumertotals <= 9000)
		{
		$level = 1; 
		$stage = 3;
		$phaselevel = 'Master';
		$nextlevel = '';
		}
	else if($eliteconsumertotals > 9000 and $eliteconsumertotals <= 9500)
		{
		$level = 2; 
		$stage = 3;
		$phaselevel = 'Master';
		}
	else if($eliteconsumertotals > 9500 and $eliteconsumertotals <= 10000)
		{
		$level = 3; 
		$stage = 3;
		$phaselevel = 'Master';
		}
	else if($eliteconsumertotals > 10000 and $eliteconsumertotals <= 10500)
		{
		$level = 1; 
		$stage = 3;
		$phaselevel = 'Wizard';
		}
	else if($eliteconsumertotals > 10500 and $eliteconsumertotals <= 11000)
		{
		$level = 2; 
		$stage = 3;
		$phaselevel = 'Wizard';
		}
	else if($eliteconsumertotals > 11000 and $eliteconsumertotals <= 11500)
		{
		$level = 3; 
		$stage = 3;
		$phaselevel = 'Wizard';
		}
	else if($eliteconsumertotals > 12000 and $eliteconsumertotals <= 12500)
		{
		$level = 1; 
		$stage = 3;
		$phaselevel = 'Oracle';
		}
	else if($eliteconsumertotals > 12500 and $eliteconsumertotals <= 13000)
		{
		$level = 2; 
		$stage = 3;
		$phaselevel = 'Oracle';
		}
	else if($eliteconsumertotals > 13000 and $eliteconsumertotals <= 13500)
		{
		$level = 3; 
		$stage = 3;
		$phaselevel = 'Oracle';
		}
	else if($eliteconsumertotals > 13500 and $eliteconsumertotals <= 14000)
		{
		$level = 1; 
		$stage = 3;
		$phaselevel = 'Legend';
		}
	else if($eliteconsumertotals > 14000 and $eliteconsumertotals <= 14500)
		{
		$level = 2; 
		$stage = 3;
		$phaselevel = 'Legend';
		}
	else if($eliteconsumertotals > 14500)
		{
		$level = 3; 
		$stage = 3;
		$phaselevel = 'Legend';
		}
	
	$level_array = array($level, $stage, $phaselevel);
	return $level_array;
	}
	
function calculate_level_topic_know ($curation_count, $view_count)
	{
	if($curation_count == false)
		{
		$level = 0;
		$phaselevel = 'Newbie';
		$step_num = 1;
		$level_array = array($level, $phaselevel, $step_num);
		return $level_array;
		}

	$level = 0;
	$phaselevel = 'Newbie';
	$step_num = 1;
	$nextlevel = '';
	$totcount = $curation_count + $view_count;
	if($totcount >= 0 and $totcount <= 50)
		{
		$level = $totcount / 100 * 100; 
		$phaselevel = 'Newbie';
		$step_num = 1;
		}
	else if($totcount > 50 and $totcount <= 75)
		{
		$level = $totcount / 100 * 100; 
		$phaselevel = 'Newbie';
		$step_num = 2;
		}
	else if($totcount > 75 and $totcount <= 100)
		{
		$level = $totcount / 100 * 100; 
		$phaselevel = 'Newbie';
		$step_num = 3;
		}
	else if($totcount > 100 and $totcount <= 150)
		{
		$level = ($totcount-100) / 250 * 100; 
		$phaselevel = 'Recruit';
		$step_num = 1;
		}
	else if($totcount > 150 and $totcount <= 250)
		{
		$level = ($totcount-100) / 250 * 100; 
		$phaselevel = 'Recruit';
		$step_num = 2;
		}
	else if($totcount > 250 and $totcount <= 350)
		{
		$level = ($totcount-100) / 250 * 100; 
		$phaselevel = 'Recruit';
		$step_num = 3;
		}
	else if($totcount > 350 and $totcount <= 500)
		{
		$level = ($totcount-350) / 550 * 100; 
		$phaselevel = 'Apprentice';
		$step_num = 1;
		}
	else if($totcount > 500 and $totcount <= 700)
		{
		$level = ($totcount-350) / 550 * 100; 
		$phaselevel = 'Apprentice';
		$step_num = 2;
		}
	else if($totcount > 700 and $totcount <= 900)
		{
		$level = ($totcount-350) / 550 * 100; 
		$phaselevel = 'Apprentice';
		$step_num = 3;
		}
	else if($totcount > 900 and $totcount <= 1200)
		{
		$level = ($totcount-900) / (2800-900) * 100; 
		$phaselevel = 'Protégé';
		$step_num = 1;
		}
	else if($totcount > 1200 and $totcount <= 1500)
		{
		$level = ($totcount-900) / (2800-900) * 100; 
		$phaselevel = 'Protégé';
		$step_num = 2;
		}
	else if($totcount > 1500 and $totcount < 2800)
		{
		$level = ($totcount-900) / (2800-900) * 100; 
		$phaselevel = 'Protégé';
		$step_num = 3;
		}
	else if($totcount > 2800 and $totcount <= 3000)
		{
		$level = ($totcount-2800) / (3400-1800) * 100; 
		$phaselevel = 'Warrior';
		$step_num = 1;
		}
	else if($totcount > 3000 and $totcount <= 3200)
		{
		$level = ($totcount-2800) / (3400-1800) * 100; 
		$phaselevel = 'Warrior';
		$step_num = 2;
		}
	else if($totcount > 3200 and $totcount <= 3400)
		{
		$level = ($totcount-2800) / (3400-1800) * 100; 
		$phaselevel = 'Warrior';
		$step_num = 3;
		}
	else if($totcount > 3400 and $totcount <= 3500)
		{
		$level = ($totcount-3400) / (5000-3000) * 100; 
		$phaselevel = 'Samurai';
		$step_num = 1;
		}
	else if($totcount > 3500 and $totcount <= 4000)
		{
		$level = ($totcount-3400) / (5000-3000) * 100; 
		$phaselevel = 'Samurai';
		$step_num = 2;
		}
	else if($totcount > 4000 and $totcount <= 5000)
		{
		$level = ($totcount-3400) / (5000-3000) * 100; 
		$phaselevel = 'Samurai';
		$step_num = 3;
		}
	else if($totcount > 5000 and $totcount <= 5500)
		{
		$level = ($totcount-5000) / (6500-5000) * 100; 
		$phaselevel = 'Ninja';
		$step_num = 1;
		}
	else if($totcount > 5500 and $totcount <= 6000)
		{
		$level = ($totcount-5000) / (6500-5000) * 100; 
		$phaselevel = 'Ninja';
		$step_num = 2;
		}
	else if($totcount > 6000 and $totcount <= 6500)
		{
		$level = ($totcount-5000) / (6500-5000) * 100; 
		$phaselevel = 'Ninja';
		$step_num = 3;
		}
	else if($totcount > 6500 and $totcount <= 7000)
		{
		$level = ($totcount-6500) / (8000-6500) * 100; 
		$phaselevel = 'Knight';
		$step_num = 1;
		}
	else if($totcount > 7000 and $totcount <= 7500)
		{
		$level = ($totcount-6500) / (8000-6500) * 100; 
		$phaselevel = 'Knight';
		$step_num = 2;
		}
	else if($totcount > 7500 and $totcount <= 8000)
		{
		$level = ($totcount-6500) / (8000-6500) * 100; 
		$phaselevel = 'Knight';
		$step_num = 3;
		}
	else if($totcount > 8000 and $totcount <= 8500)
		{
		$level = ($totcount-8000) / (9500-8000) * 100; 
		$phaselevel = 'Master';
		$step_num = 1;
		}
	else if($totcount > 8500 and $totcount <= 9000)
		{
		$level = ($totcount-8000) / (9500-8000) * 100; 
		$phaselevel = 'Master';
		$step_num = 2;
		}
	else if($totcount > 9000 and $totcount <= 9500)
		{
		$level = ($totcount-8000) / (9500-8000) * 100; 
		$phaselevel = 'Master';
		$step_num = 3;
		}
	else if($totcount > 9500 and $totcount <= 10000)
		{
		$level = ($totcount-9500) / (11000-9500) * 100; 
		$phaselevel = 'Wizard';
		$step_num = 1;
		}
	else if($totcount > 10000 and $totcount <= 10500)
		{
		$level = ($totcount-9500) / (11000-9500) * 100; 
		$phaselevel = 'Wizard';
		$step_num = 2;
		}
	else if($totcount > 10500 and $totcount <= 11000)
		{
		$level = ($totcount-9500) / (11000-9500) * 100; 
		$phaselevel = 'Wizard';
		$step_num = 3;
		}
	else if($totcount > 11000 and $totcount <= 11500)
		{
		$level = ($totcount-11000) / (12500-11000) * 100; 
		$phaselevel = 'Oracle';
		$step_num = 1;
		}
	else if($totcount > 11500 and $totcount <= 12000)
		{
		$level = ($totcount-11000) / (12500-11000) * 100; 
		$phaselevel = 'Oracle';
		$step_num = 2;
		}
	else if($totcount > 12000 and $totcount <= 12500)
		{
		$level = ($totcount-11000) / (12500-11000) * 100; 
		$phaselevel = 'Oracle';
		$step_num = 3;
		}
	else if($totcount > 12500 and $totcount <= 13000)
		{
		$level = ($totcount-12500) / (13500-12500) * 100; 
		$phaselevel = 'Legend';
		$step_num = 1;
		}
	else if($totcount > 13000 and $totcount <= 13500)
		{
		$level = ($totcount-12500) / (13500-12500) * 100; 
		$phaselevel = 'Legend';
		$step_num = 2;
		}
	else if($totcount > 13500)
		{
		$level = 100; 
		$phaselevel = 'Legend';
		$step_num = 3;
		}
	
	$level_array = array($level, $phaselevel, $step_num);
	return $level_array;
	}
	
function nextlevel($currlevel='')
	{
	if($currlevel == '')
		return 'Newbie';
	else if($currlevel == 'Newbie')
		return 'Recruit';
	else if($currlevel == 'Recruit')
		return 'Apprentice';
	else if($currlevel == 'Apprentice')
		return 'Protégé';
	else if($currlevel == 'Protégé')
		return 'Warrior';
	else if($currlevel == 'Warrior')
		return 'Samurai';
	else if($currlevel == 'Samurai')
		return 'Ninja';
	else if($currlevel == 'Ninja')
		return 'Knight';
	else if($currlevel == 'Knight')
		return 'Master';
	else if($currlevel == 'Master')
		return 'Wizard';
	else if($currlevel == 'Wizard')
		return 'Oracle';
	else if($currlevel == 'Oracle')
		return 'Legend';
	else if($currlevel == 'Legend')
		return 'Legend';
		
	}
function alert($msg)
{
    $msg = addslashes($msg);
    $msg = str_replace("\n", "\\n", $msg);
    echo "<script language='javascript'><!--\n";
    echo 'alert("' . $msg . '")';
    echo "//--></script>\n\n";
}
function send_email($from, $to, $subject, $body)
	{
	$max_size = 1024*4096;
	$eol      = "\r\n";
	$boundary = md5(uniqid(time()));

	//now send the email

	$headers  = "From: $from$eol";
	$headers .= 'MIME-Version: 1.0'.$eol;
	$headers .= 'Content-Type: text/html; boundary="'.$boundary.'"'.$eol;

	mail($to, $subject, $body, $headers);
	}
function convert_smart_quotes($string) 
{ 
    $search = array(chr(145), 
                    chr(146), 
                    chr(147), 
                    chr(148), 
                    chr(151)); 
 
    $replace = array("'", 
                     "'", 
                     '"', 
                     '"', 
                     '-'); 
 
    return str_replace($search, $replace, $string); 
} 
function isValidEmail($email){
	if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return true;
}
	return false;
}

function whichserver()
	{
	$http_host = strtolower($_SERVER['HTTP_HOST']);
	$pos = strpos($http_host, 'getsokno.com');
	if($pos !== false)
		{
		return 'Production';
		}
	else
		return 'Development';
	}
?>