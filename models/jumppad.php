<?php

require_once('../libraries/db/database.inc.php');

class jumpPadModel
{

	public $kcells;

	function loadData($query)
	{
		$db = le_dbconnect();
		
		$myquery = "select topic from topics order by topic limit 20";
		$res_qu = mysql_query($myquery);
		$res_array = db_result_to_array($res_qu);
		
		//$this->kcells = array ( 101 => "Art", 102 => "Literature", 103 => "Math", 104 => "Science", 105 => "Zooology" );
		
		foreach($res_array as $row)
			$this->kcells[] = substr($row['topic'],0,15);
	}

}

?>
