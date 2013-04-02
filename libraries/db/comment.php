<?php

require_once('database.php');

class Comments extends DatabaseObject
	{
	
	protected static $table_name = "comments";
	protected static $db_fields=array('commentid','parentid','content_type','contentid','curationid', 'title', 'body',
									  'comment_date_added','userguid','link', 'file_upload', 'privateid');
	
	public $commentid;
	public $parentid;
	public $content_type;
	public $contentid;
	public $curationid;
	public $title;
	public $body;
	public $comment_date_added;
	public $userguid;
	public $link;
	public $file_upload;
	public $privateid;
	
	public static function find_last_comment($commentid=0, $bycontent=2)
		{
		global $database;
		$sql = "select * from ". self::$table_name;
		if($bycontent == 2) //find by contentid
			{
			$sql .= " where contentid = " .$database->escape_value($commentid);
			$sql .= " and content_type = 1";
			}
		else
			{
			$sql .= " where (commentid = " .$database->escape_value($commentid);
			$sql .= " or parentid = " .$database->escape_value($commentid);
			$sql .= " ) and content_type = 1";
			}
		$sql .= " order by commentid desc limit 1";
		$session->message .= '--find_last_comment sql: '.$sql;
		$result_array = self::find_by_sql($sql);
		return !empty($result_array) ? $result_array : false;
		}
		
	public static function related_content($commentid=0)
		{
		global $database;
		$sql = "select * from ". self::$table_name;
		$sql .= " where commentid = " .$database->escape_value($commentid);
		$sql .= " and linked_content_id != 0 ";
		$sql .= " limit 3";
		$session->message .= '--find related content sql: '.$sql;
		$result_array = self::find_by_sql($sql);
		return !empty($result_array) ? $result_array : false;
		}
		
	public static function count_users_on_thread($commentid=0)
		{
		global $database;
		$sql = "select distinct userguid from ". self::$table_name;
		$sql .= " where commentid = " .$commentid. " or parentid = ".$commentid;
		//$session->message .= '--find_last_comment sql: '.$sql;
		$result_array = self::find_by_sql($sql);
		return !empty($result_array) ? count($result_array) : 0;
		}
		
	public static function count_comments($commentid)
		{
		global $database;
		$likes = 0;
		$sql = "select count(*) from comments where commentid = ".$commentid." or parentid = ".$commentid;
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		return array_shift($row); //gets the 1st item in the row
		}
	
	public static function count_user_comments($userguid, $topic=0)
		{
		global $database;
		$likes = 0;
		$sql = "select count(*) from comments where userguid = ".$userguid;
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		return array_shift($row); //gets the 1st item in the row
		}
	
	public static function count_comments_new($commentid)
		{
		global $database;
		$likes = 0;
		$sql = "select count(*) from comments where commentid = ".$commentid." or parentid = ".$commentid;
		$session->message .= "<br/>comment: ".$sql;
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		return array_shift($row); //gets the 1st item in the row
		}
	
	public static function count_comments_topic($userguid, $topicid)
		{
		global $database;
		$likes = 0;
		$sql = "select count(*) from comments as c, curations as u where c.curationid = u.curationid and
																		c.userguid = ".$userguid." and u.topicid = ".$topicid;
		$session->message .= "<br/>comment: ".$sql;
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		return array_shift($row); //gets the 1st item in the row
		}
	
	public static function count_comment_likes($commentid)
		{
		global $database;
		$likes = 0;
		$sql = "select count(*) from user_likes_flags where commentid = ".$commentid. " and like_unlike = 1 ";
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		return array_shift($row); //gets the 1st item in the row
		}
	
	public static function count_comment_follows($commentid)
		{
		global $database;
		$likes = 0;
		$sql = "select count(*) from user_follows_comments where commentid = ".$commentid." ";
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		return array_shift($row); //gets the 1st item in the row
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
		$sql = "select * from ".self::$table_name." where commentid={$database->escape_value($id)} limit 1";
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
		return isset($this->commentid) ? $this->update() : $this->create();
		}
		
	public function create()
		{
		global $database;
		global $session;
		
		$attributes = $this->sanitized_attributes();
		//don't forget to do good SQL and escape chars
		$sql = "insert into ".self::$table_name." (";
		//$sql .= "email, password, first_name, last_name";
		$sql .= join(", ", array_keys($attributes));
		$sql .= ") values ('";
		$sql .= join("', '", array_values($attributes));
		$sql .= "')";
		$session->message .= '<br/>create thread:'.$sql;
		if($database->query($sql))
			{
			$this->commentid = $database->insert_id();
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
		$sql .= " Where commentid = ". $database->escape_value($this->commentid) ;
		
		$database->query($sql);
		return ($database->affected_rows() == 1) ? true : false;
		}
	
	public function delete()
		{
		global $database;
		
		$sql = "delete from ".self::$table_name;
		$sql .= " where commentid = " . $database->escape_value($this->commentid);
		$sql .= " limit 1";
		
		$database->query($sql);
		return ($database->affected_rows() == 1) ? true : false;
		}
			
	
	}

?>