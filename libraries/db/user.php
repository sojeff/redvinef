<?php
require_once("database.php");

class User extends DatabaseObject
	{
	
	protected static $table_name = "users";
	protected static $db_fields = array('userguid', 'name', 'first_name', 'last_name',
					    'link', 'birthday', 'gender', 'email', 'timezone',
					    'locale', 'verified', 'signup_code', 'updated_time', 'password', 'user_status',
					    'avatar', 'can_create_private', 'private_cell_name',
					    'wherefromcity', 'wherefromstate','wherefromzip', 'wherefromcountry', 
                        'currentlocationcity','currentlocationstate','currentlocationzip', 'currentlocationcountry',
                        'foreign_key', 'last_login', 'login_count', 'login_fails_since_succ');
	public $userguid;
	public $name;
	public $first_name;
	public $last_name;
	public $link;
	public $birthday;
	public $gender;
	public $email;
	public $timezone;
	public $locale;
	public $verified;
	public $signup_code;
	public $updated_time;
	public $password;
	public $user_status;
	public $avatar;
	public $can_create_private;
	public $private_cell_name;
	public $wherefromcity;
	public $wherefromcountry;
	public $wherefromstate;
        public $wherefromzip;
	public $currentlocationcity;
        public $currentlocationstate;
        public $currentlocationzip;
	public $currentlocationcountry;
	public $foreign_key;
	public $last_login;
	public $login_count;
	public $login_fails_since_succ;
	
	public static function authenticate($username="", $password="", $auth = 1)
		{
		global $database;
		$dt = date('Y-m-d H:i:s');
		$username = $database->escape_value($username);
		$password = $database->escape_value($password);
		$myauth = '';
		if($auth == 1)
			$myauth = 'and verified = 1 ';
		$sql = "select * from users where email like '{$username}' and password = '{$password}' {$myauth} limit 1";
		$result_array = self::find_by_sql($sql);
		if(empty($result_array))
			{
			//failed login...update counts if username exists
			$sql2 = "select * from users where email like '{$username}' limit 1";
			$result_array2 = self::find_by_sql($sql2);
			if(!empty($result_array2))
				{
				$sql3 = "update users set last_login = '$dt', login_fails_since_succ = login_fails_since_succ + 1 
						 where email like '{$username}' limit 1";
				$res = mysql_query($sql3);  /// need get rid of this because it ties us to mysql...
				}
				
			}
		else
			{
			$sql3 = "update users set last_login = '$dt', login_fails_since_succ = 0, login_count = login_count + 1 
					 where email like '{$username}' limit 1";
			$res = mysql_query($sql3);  /// need get rid of this because it ties us to mysql...
			}
		return !empty($result_array) ? array_shift($result_array) : false;
		
		}
	
	public function full_name() 
		{
		if(isset($this->first_name) && isset($this->last_name))
			{
			return $this->first_name . " " . $this->last_name;
			}
		else
			return "";
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
		
	//make it a class function (not an instance function) with "static" access via ::
	
	
	}

?>