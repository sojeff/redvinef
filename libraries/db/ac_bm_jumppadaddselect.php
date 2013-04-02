<?php
 
// if the 'term' variable is not sent with the request, exit
//if ( !isset($_REQUEST['tag-community']) )
	//exit;
   
// connect to the database server and select the appropriate database for use

//$dblink = mysql_connect('server', 'username', 'password') or die( mysql_error() );
//mysql_select_db('database_name');
 
// query the database table for zip codes that match 'term'

//$rs = mysql_query('select zip, city, state from zipcode where zip like "'. mysql_real_escape_string($_REQUEST['term']) .'%" order by zip asc limit 0,10', $dblink);
 
// loop through each zipcode returned and format the response for jQuery

//$data = array();


if (0==0)// $rs && mysql_num_rows($rs) )
{
//	while( $row = mysql_fetch_array($rs, MYSQL_ASSOC) )
//	{
//		$data[] = array(
//			'label' => $row['zip'] .', '. $row['city'] .' '. $row['state'] ,
//			'value' => $row['zip']
//		);
//	}
    
    $data = array('label'=>"test", 'value'=>65);
}
 
// jQuery wants JSON data
//echo '<h1>Hello</h1>';

echo json_encode($data);
flush();