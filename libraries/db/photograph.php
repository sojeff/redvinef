<?php
require_once("database.php");

class Photograph extends DatabaseObject
	{
	
	protected static $table_name = "photographs";
	protected static $db_fields = array('id', 'filename', 'type', 'size',
										'caption', 'userguid', 'date_created', 'image');
	public $id;
	public $filename;
	public $type;
	public $size;
	public $caption;
	public $userguid;
	public $date_created;
	public $user_status;
	public $image;
	
	private $temp_path;
	protected $upload_dir = "img";
	
	public $errors = array();
	
	protected $upload_errors = array(
		UPLOAD_ERR_OK 			=> "No errors.",
		UPLOAD_ERR_INI_SIZE 	=> "Larger than upload_max_filesize.",
		UPLOAD_ERR_FORM_SIZE	=> "Larger than form MAX_FILE_SIZE.",
		UPLOAD_ERR_PARTIAL 		=> "Partial upload.",
		UPLOAD_ERR_NO_FILE 		=> "No file.",
		UPLOAD_ERR_NO_TMP_DIR	=> "No temporary directory.",
		UPLOAD_ERR_CANT_WRITE	=> "Can't write to disk.",
		UPLOAD_ERR_EXTENSION	=> "File upload stopped by extension."
		);
		
	// pass in $_FILE(['uploaded_file']) as an argument
	public function attach_file($file)
		{
		//perform error checking on the form parameters
		if(!$file || empty($file) || !is_array($file))
			{
			//error: nothing uploaded or wrong argument usage
			$this->errors[] = "No file was uploaded.";
			return false;
			}
		else if($file['error'] != 0)
			{
			//error: report what PHP says went wrong
			$this->errors[] = $this->upload_errors[$file['error']];
			}
		else
			{
			//set object attributes to the form parameters.
			$this->temp_path	= $file['tmp_name'];
			$this->filename		= basename($file['name']);
			$this->type			= $file['type'];
			$this->size			= $file['size'];
			//don't worry about saving to db yet.
			return true;
			}
		}
		
	public function save()
		{
		//a new record won't have an id yet
		if(isset($this->id))
			{
			$this->update();
			}
		else
			{
			//make sure there are no errors
			//can't save if there are pre-existing errors
			if(!empty($this->errors)) { return false; }
			
			//make sure the caption is not too long for the db
			if(strlen($this->caption) >= 255)
				{
				$this->errors[] = "The caption can only be 255 characters long.";
				return false;
				}
			
			//can't save without filename and temp location
			if(empty($this->filename) || empty($this->temp_path))
				{
				$this->errors[] = "The file location was not available.";
				return false;
				}
				
			//determine the target path
			$target_path = CONTROLLERS_PATH.DS.$this->upload_dir.DS.$this->filename;
			
			//make sure a file doesn't already exist in the target location
			if(file_exists($target_path))
				{
				$this->errors[] = "The file {$this->filename} already exists.";
				return false;
				}

			//attempt to move the file
			if(move_uploaded_file($this->temp_path, $target_path))
				{
				//success
				//save a corresponding entry to the Database
				if($this->create())
					{
					unset($this->temp_path);
					return true; 
					}
				}
			else
				{
				//failure
				$this->errors[] = "The file {$target_path} upload failed, possibly due to incorrect permissions on the upload folder.";
				return false;
				}
			}
		}
		
	public function destroy() {
		// First remove the database entry
		if($this->delete()) {
			// then remove the file
		  // Note that even though the database entry is gone, this object 
			// is still around (which lets us use $this->image_path()).
			$target_path = CONTROLLERS_PATH.DS.$this->image_path();
			return unlink($target_path) ? true : false;
		} else {
			// database delete failed
			return false;
		}
	}
	
	public function image_path() {
	  return $this->upload_dir.DS.$this->filename;
	}
	
	public function size_as_text() {
		if($this->size < 1024) {
			return "{$this->size} bytes";
		} elseif($this->size < 1048576) {
			$size_kb = round($this->size/1024);
			return "{$size_kb} KB";
		} else {
			$size_mb = round($this->size/1048576, 1);
			return "{$size_mb} MB";
		}
	}
	
	public function comments() {
		return Comment::find_comments_on($this->id);
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
		$sql = "select * from ".self::$table_name." where id={$database->escape_value($id)} limit 1";
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
		
	//made a custom save above with additional functionality	
//	public function save()
//		{
//		//a new record won't have an id so we use save() for both create and update
//		return isset($this->id) ? $this->update() : $this->create();
//		}
		
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
			$this->id = $database->insert_id();
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
		$sql .= " Where id = ". $database->escape_value($this->id) ;
		
		$database->query($sql);
		return ($database->affected_rows() == 1) ? true : false;
		}
	
	public function delete()
		{
		global $database;
		
		$sql = "delete from ".self::$table_name;
		$sql .= " where id = " . $database->escape_value($this->id);
		$sql .= " limit 1";
		
		$database->query($sql);
		return ($database->affected_rows() == 1) ? true : false;
		}
		
	//make it a class function (not an instance function) with "static" access via ::
	
	
	}

?>