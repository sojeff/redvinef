<?php
require_once("database.php");

class User_likes_flags extends DatabaseObject
	{
	
	protected static $table_name = "user_likes_flags";
	protected static $db_fields = array('user_likes_flagsid', 'like_unlike', 'curationid', 'contentid',
										'topicid', 'commentid', 'userid', 'date_created');
	public $user_likes_flagsid;
	public $like_unlike;
	public $curationid;
	public $contentid;
	public $topicid;
	public $commentid;
	public $userid;
	public $date_created;
	
	public $set_like; //set the like for a user on content 1 or comment 2
	public $count_likes; //count the # of likes for content 1 or comment 2
			
	//class methods		

	public static function content_liked()
		{
		global $database;
		global $session;
		
		$sql  = "select * from ".self::$table_name." where curationid = ".$session->curationid." and ";
		$sql .= "contentid = ".$session->contentid." and ";
		$sql .= "topicid = ".$session->user_knocell." and ";
		$sql .= "userid = ".$session->user_id." limit 1";

		$result_array = self::find_by_sql($sql);
		return !empty($result_array) ? array_shift($result_array) : false;
		}
	
	public static function comment_liked($commentid)
		{
		global $database;
		global $session;
		
		$sql  = "select * from ".self::$table_name." where curationid = ".$session->curationid." and ";
		$sql .= "commentid = ".$commentid." and ";
		$sql .= "topicid = ".$session->user_knocell." and ";
		$sql .= "userid = ".$session->user_id." limit 1";

		$result_array = self::find_by_sql($sql);
		return !empty($result_array) ? array_shift($result_array) : false;
		}
	
	public static function set_like_content()
		{
		global $database;
		global $session;
		$sql  = "select * from ".self::$table_name." where curationid = ".$session->curationid." and ";
		$sql .= "contentid = ".$session->contentid." and ";
		$sql .= "topicid = ".$session->user_knocell." and ";
		$sql .= "userid = ".$session->user_id." limit 1";
		$session->message .= '--set_like_content sql:'.$sql;
		return self::find_by_sql($sql);		
		
		}
	
	public static function set_like_comment($commentid)
		{
		global $database;
		global $session;
		$sql  = "select * from ".self::$table_name." where curationid = ".$session->curationid." and ";
		$sql .= "commentid = ".$commentid." and ";
		$sql .= "topicid = ".$session->user_knocell." and ";
		$sql .= "userid = ".$session->user_id." limit 1";
		$session->message .= '--set_like_content sql:'.$sql;
		return self::find_by_sql($sql);		
		
		}
	
	public static function persona_cells($per_page, $offset)
		{
		global $database;
		global $session;
		$where = "where t.contentid = c.contentid and c.userguid = u.userguid and c.topicid = ".$session->user_knocell." ";
		if($session->user_view == 'personal' and 1==2)
			$where .= "and c.userguid = ".$session->user_id;
			
		if($session->user_view != 'private')
			$where .= "and t.privateid = 0 and c.privateid = 0 ";
			
		if($session->knocell_view == 'Content')
			$where .= "and c.content_type = 0 ";
		else if($session->knocell_view == 'Threads')
			$where .= "and c.content_type = 1 ";
		else if($session->knocell_view == 'QandA')
			$where .= "and c.content_type = 2 ";
		//else if($session->knocell_view == 'Personas')
		//	$where .= " and 1=1 ";
			
		if($session->user_search != '' and strtolower($session->user_search) != 'find knowledge')
			{
			$where .= " and (t.url like '%".$session->user_search."%' or 
			                 t.title like '%".$session->user_search."%' or 
			                 t.source_website like '%".$session->user_search."%' or
			                 c.tags like '%".$session->user_search."%' ) ";
			}
		$sort = '';
		if($session->user_sort == 'Date')
			$sort = " order by c.curationid desc";
		else if($session->user_sort == 'Alphabetical')
			$sort = " order by u.name asc";
		else if($session->user_sort == 'Content')
			$sort = " order by t.title asc";
		else if($session->user_sort == 'Relevance')
			$sort = " order by c.rank desc, t.title asc";
			
		$sql = "select distinct c.userguid, u.name from ".self::$table_name." as t, curations as c, users as u $where  $sort";
		$sql .= " LIMIT {$per_page} ";
		$sql .= " OFFSET {$offset}";
		$session->message .= '--persona_cells sql:'.$sql;
		
		$topicid_set = array();
		$topiccount = 0;
		$result_set = $database->query($sql);
		$rows = array();
		while($row = $database->fetch_array($result_set))
			{
			$rows[] = $row;
			}
		return $rows;
		
		}
	
	public static function cell_likes($contentid)
		{
		global $database;
		$likes = 0;
		$sql = "select likes from curations where contentid = ".$contentid;
		$result_set = $database->query($sql);
		while($row = $database->fetch_array($result_set))
			{
			$likes = $likes + $row['likes'];
			}
		return $likes;
		}
		
	public static function count_likes_topic($userguid, $topicid=0)
		{
		global $database;
		$likes = 0;
		
		$addwhere = "";
		if($topicid != 0)
			$addwhere = " and topicid = ".$topicid;
		$sql = "select count(*) from ".self::$table_name." where userid = '$userguid' ".$addwhere;
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		return array_shift($row); //gets the 1st item in the row
		}
		
	public static function cell_comments($contentid)
		{
		global $database;
		$likes = 0;
		$sql = "select count(*) from comments where contentid = ".$contentid;
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		return array_shift($row); //gets the 1st item in the row
		}
		
	public static function cell_curator($contentid)
		{
		global $database;
		$likes = 0;
		$sql = "select userguid from curations where contentid = ".$contentid." order by curationid desc limit 1";
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		return array_shift($row); //gets the 1st item in the row
		}
		
	public static function cell_image($contentid)
		{
		global $database;
		$sql = "select image_id from content_image where contentid = ".$contentid." order by image_id asc limit 1 ";
		$result_set = $database->query($sql);
		$object_array = array();
		while($row = $database->fetch_array($result_set))
			{
			return array_shift($row);
			}
		return false;
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
		$sql = "select * from ".self::$table_name." where user_likes_flagsid={$database->escape_value($id)} limit 1";
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
		return isset($this->user_likes_flagsid) ? $this->update() : $this->create();
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
			$this->user_likes_flagsid = $database->insert_id();
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
		$sql .= " Where user_likes_flagsid = ". $database->escape_value($this->user_likes_flagsid) ;
		
		$database->query($sql);
		return ($database->affected_rows() == 1) ? true : false;
		}
	
	public function delete()
		{
		global $database;
		
		$sql = "delete from ".self::$table_name;
		$sql .= " where user_likes_flagsid = " . $database->escape_value($this->user_likes_flagsid);
		$sql .= " limit 1";
		
		$database->query($sql);
		return ($database->affected_rows() == 1) ? true : false;
		}
		
	
	}

?>