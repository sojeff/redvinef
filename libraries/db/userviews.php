<?php
require_once("database.php");

class Userview extends DatabaseObject
	{
	
	protected static $table_name = "userviews";
	protected static $db_fields = array('userviewsid', 'userguid', 'contentid', 'date_added', 'topicid');
	public $userviewsid;
	public $userguid;
	public $contentid;
	public $date_added;
	public $topicid;
	
	public static function top_interest($userguid = "", $count = 1, $choice = 1)
		{ 
		global $database;
		global $session;
		
		if($userguid == '')
			return false;
			
		$where = "WHERE userguid =  ". $userguid ." and topicid != 0 ";

		$sql = "SELECT topicid, COUNT( topicid ) 
					FROM  ".self::$table_name."
					".$where."
					GROUP BY topicid
					ORDER BY COUNT( topicid ) DESC 
					LIMIT 6";

		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		if($row)
			$mychoice = array_shift($row); //gets the 1st item in the row
		else
			$mychoice = 0;
		
		if($choice == 2 and $row)
			{
			$row = $database->fetch_array($result_set);
			if($row)
				$mychoice = array_shift($row); //gets the 2nd item in the row
			else
				$mychoice = 0;
			}
		if($choice == 3 and $row)
			{
			$row = $database->fetch_array($result_set);
			if($row)
				$mychoice = array_shift($row); //gets the 2nd item in the row
			else
				$mychoice = 0;
			$row = $database->fetch_array($result_set);
			if($row)
				$mychoice = array_shift($row); //gets the 3rd item in the row
			else
				$mychoice = 0;
			}
		if($row)
			return $mychoice; //gets the 1st item in the row
		else
			return 0;
		}
		
	public static function count_views_topic($userguid = "", $topicid = 0)
		{ 
		global $database;
		global $session;
		
		if($userguid == '')
			return false;
			
		$where = "WHERE userguid =  ". $userguid ." and topicid = ".$topicid;

		$sql = "SELECT count(*) 
					FROM  ".self::$table_name."
					".$where;

		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		if($row)
			return array_shift($row); //gets the 1st item in the row
		else
			return 0;
		}
		
	protected function attributes()
		{
		//return an array of attribute keys and their values
		$attributes = array();
		foreach(self::$db_fields as $field)
			{
			if(property_exists($this, $field))
				{
				$attributes[$field] = $this->$field;
				}
			}
		return $attributes;
		}
	
	protected function sanitized_attributes()
		{
		global $database;
		$clean_attributes = array();
		//sanitize the values before submitting
		//note: does not alter the actual value of each ATTRIBUTE
		foreach($this->attributes() as $key => $value)
			{
			$clean_attributes[$key] = $database->escape_value($value);
			}
		return $clean_attributes;
		}

	// common database methods
	public static function find_all()
		{
		return self::find_by_sql("select * from ".self::$table_name);
		}
		
	public static function find_by_id($id=0)
		{
		global $database;
		$sql = "select * from ".self::$table_name." where userviewsid={$database->escape_value($id)} limit 1";
		$result_array = self::find_by_sql($sql);
		return !empty($result_array) ? array_shift($result_array) : false;
		}
		
	public static function find_by_sql($sql="")
		{
		global $database;
		$result_set = $database->query($sql);
		$object_array = array();
		while($row = $database->fetch_array($result_set))
			{
			$object_array[] = self::instantiate($row);
			}
		return $object_array;
		}
	
	public static function count_all()
		{
		global $database;
		$sql = "select count(*) from ".self::$table_name;
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		return array_shif($row); //gets the 1st item in the row
		}
		
	private static function instantiate($record)
		{
		$object = new self;
		//dynamically create the object from the table
		foreach($record as $attribute=>$value)
			{
			if($object->has_attribute($attribute))
				{
				$object->$attribute = $value;
				}
			}
		return $object;
		}
		
	private function has_attribute($attribute)
		{
		//get_object_vars returns an associative array with all attributes
		//(incl. private ones!) as the keys and their current values as the value
		$object_vars = get_object_vars($this);
		//we don't care about the value, we just want to know if the key exists 
		//will return true or false
		return array_key_exists($attribute, $object_vars);
		}
		
		
	public function save()
		{
		//a new record won't have an id so we use save() for both create and update
		return isset($this->userviewsid) ? $this->update() : $this->create();
		}
		
	public function create()
		{
		global $database;
		$attributes = $this->sanitized_attributes();
		//don't forget to do good SQL and escape chars
		$sql = "insert into ".self::$table_name." (";
		//$sql .= "email, password, first_name, last_name";
		$sql .= join(", ", array_keys($attributes));
		$sql .= ") values ('";
		$sql .= join("', '", array_values($attributes));
		$sql .= "')";
		if($database->query($sql))
			{
			$this->userguid = $database->insert_id();
			return true;
			}
		else
			return false;
		}

	public function update()
		{
		global $database;
		$attributes = $this->sanitized_attributes();
		foreach($attributes as $key => $value)
			{
			$attribute_pairs[] = "{$key} = '{$value}'";
			}
		$sql = "update ".self::$table_name." set ";
		$sql .= join(", ", $attribute_pairs);
		$sql .= " Where userviewsid = ". $database->escape_value($this->userguid) ;
		
		$database->query($sql);
		return ($database->affected_rows() == 1) ? true : false;
		}
	
	public function delete()
		{
		global $database;
		
		$sql = "delete from ".self::$table_name;
		$sql .= " where userviewsid = " . $database->escape_value($this->userguid);
		$sql .= " limit 1";
		
		$database->query($sql);
		return ($database->affected_rows() == 1) ? true : false;
		}
		
	//make it a class function (not an instance function) with "static" access via ::
	
	
	}

?>