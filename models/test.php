<?php

// require_once('../libraries/db/database.inc.php');
// require_once('../libraries/img/image.inc.php');

class testModel
{

	public $var1;
	public $var2;
	public $var3;
	public $var4;

	function loadData($query)
	{
		$this->var1 = $query;
		$this->var2 = "This is My Paragraph... bla bla bla bla!";
		$this->var3 = "This is My Heading 2 dude!";
		$this->var4 = array ( "My Art" => 101, "Literature" => 102, "Math" => 103, "Science" => 104 );
	}

}

?>
