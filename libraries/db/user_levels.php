<?php
require_once("database.php");

class User_levels extends DatabaseObject
	{
	
	protected static $table_name = "user_levels";
	protected static $db_fields = array('userguid', 'pages_viewed', 'following_count',
										'followed_count', 'curations_count', 'last_modified', 'rank', 'likes_count', 'flags_count', 'tags_count', 'shares_count');
	public $userguid;
	public $pages_viewed;
	public $following_count;
	public $followed_count;
	public $curations_count;
	public $last_modified;
	public $rank;
	public $likes_count;
	public $flags_count;
	public $tags_count;
	public $shares_count;
	
	public $set_like; //set the like for a user on content 1 or comment 2
	public $count_likes; //count the # of likes for content 1 or comment 2
			
	//class methods		

	public static function get_elite_private($limit)
		{
		global $database;
		global $session;
		
		$yesterday = time() - (60*60*24*35);
		$yest = date('Y-m-d',$yesterday);
		
		$sql  = "select * from ".self::$table_name." as ul, users as u where u.userguid = ul.userguid and u.private_cell_name != '' And
		         (followed_count > 0 or curations_count > 0 or 
					(pages_viewed > 0 or likes_count > 0)) and last_modified >= '$yest' 
					order by curations_count desc, followed_count desc, pages_viewed desc, likes_count desc limit ".$limit;

		$result_array = self::find_by_sql($sql);
		return !empty($result_array) ? $result_array : false;
		}	

	public static function get_elite_private_cell($limit, $user_knocell=0)
		{
		global $database;
		global $session;
		
		$yesterday = time() - (60*60*24*35);
		$yest = date('Y-m-d',$yesterday);
		if($user_knocell == 0)
			$user_knocell = $session->user_knocell;
			
		$sql  = "select distinct ul.userguid from  ".self::$table_name." as ul, curations as c
		        where c.topicid = '$user_knocell' and ul.userguid = c.userguid and  
		            (followed_count > 0 or curations_count > 0 or 
					(pages_viewed > 0 or likes_count > 0)) and ul.last_modified >= '$yest' 
				order by curations_count desc, followed_count desc, pages_viewed desc, likes_count desc limit ".$limit;
		//$session->message = $sql;
		$result_array = self::find_by_sql($sql);
		return !empty($result_array) ? $result_array : false;
		}	

	public static function get_elite($limit)
		{
		global $database;
		global $session;
		
		$yesterday = time() - (60*60*24*35);
		$yest = date('Y-m-d',$yesterday);
		
		$sql  = "select * from ".self::$table_name." where (followed_count > 0 or curations_count > 0 or 
					(pages_viewed > 0 or likes_count > 0)) and last_modified >= '$yest' 
					order by curations_count desc, followed_count desc, pages_viewed desc, likes_count desc limit ".$limit;
		$result_array = self::find_by_sql($sql);
		return !empty($result_array) ? $result_array : false;
		}	

	public static function increment_follow($userguid, $followed=1)
		{
		global $database;
		global $session;
		
		if($followed == 1)
			$sql = "update ".self::$table_name." set following_count = following_count + 1 where userguid = ".$userguid." limit 1";
		else
			$sql = "update ".self::$table_name." set followed_count = followed_count + 1 where userguid = ".$userguid." limit 1";
		$result_set = $database->query($sql);
		return true; //gets the 1st item in the row
		}
		
		
	// common database methods

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

	public static function find_all()
		{
		return self::find_by_sql("select * from ".self::$table_name);
		}
		
	public static function find_by_id($id=0)
		{
		global $database;
		$sql = "select * from ".self::$table_name." where userguid={$database->escape_value($id)} limit 1";
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
		return array_shift($row); //gets the 1st item in the row
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
		return isset($this->userguid) ? $this->update() : $this->create();
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
		$sql .= " Where userguid = ". $database->escape_value($this->userguid) ;
		
		$database->query($sql);
		return ($database->affected_rows() == 1) ? true : false;
		}
	
	public function delete()
		{
		global $database;
		
		$sql = "delete from ".self::$table_name;
		$sql .= " where userguid = " . $database->escape_value($this->userguid);
		$sql .= " limit 1";
		
		$database->query($sql);
		return ($database->affected_rows() == 1) ? true : false;
		}
		
	
	}

?>