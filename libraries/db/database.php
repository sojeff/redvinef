<?php
//database.php


require_once(LIB_PATH.DS."config.php");

class mySQLDatabase 
{
//database connection
	private $connection;
	private $magic_quotes_active;
	private $real_escape_string_exists;
	public $last_query;
	
	function __construct()
		{
		$this->open_connection();
		$this->magic_quotes_active = get_magic_quotes_gpc();
		$this->real_escape_string_exists = function_exists("mysql_real_escape_string"); //i.e. php >= v4.3.0
		}
		
	public function open_connection()
	{
	$this->connection = mysql_pconnect(DB_SERVER, DB_USER, DB_PASS);
	if(!$this->connection) 
		{
		die("Database connection failed: " . mysql_error());
		}
	else
		{
		$db_select = mysql_select_db(DB_NAME, $this->connection);
		if(!$db_select)
			{
			die("Database selection failed: " . mysql_error());
			}
		}
	}
	
	public function close_connection()
		{
		if(isset($this->connection))
			{
			mysql_close($this->connection);
			unset($this->connection);
			}
		}
		
	public function query($sql)
		{
		$this->last_query = $sql;
		$result = mysql_query($sql, $this->connection);
		$this->confirm_query($result);
		return $result;
		}
		
	public function db_result_to_array($result)
		{
	    $res_array = array();
	
	   	for ($count=0; $row = @mysql_fetch_array($result); $count++)
		 		$res_array[$count] = $row;
	
	    return $res_array;
		}
		
	public function escape_value($value)
		{
		if($this->real_escape_string_exists)
			{
			if($this->magic_quotes_active)
				{
				$value = stripslashes($value);
				}
			$value = mysql_real_escape_string($value);
			}
		else
			{
			if(!$this->magic_quotes_active)
				$value = addslashes($value);
			}
		return $value;
		}
//database neutral methods
	
	public function fetch_array($result)
		{
		return mysql_fetch_array($result);
		}
	
	public function num_rows($result)
		{
		return mysql_num_rows($result);
		}
	
	public function insert_id()
		{
		//get the last id inserted or updated
		return mysql_insert_id($this->connection);
		}
	
	public function affected_rows()
		{
		//get the last id inserted or updated
		return mysql_affected_rows($this->connection);
		}
	
	private function confirm_query($result)
		{
		if(!$result)
			{
			$output = "Database query failed: " . mysql_error() ."<br/><br/>";
			$output .= "Last SQL query: " . $this->last_query;
			die($output);
			}
		}
			
} //end of mySQLDatabase class

$database = new mySQLDatabase();
$db =& $database;

?>