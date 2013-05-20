<?php
require_once("database.php");

class Jumppad extends DatabaseObject
	{
	
	protected static $table_name = "curations";
	protected static $db_fields = array('curationid', 'userguid', 'topicid', 'content_type',
										'contentid', 'commentid', 'rank', 'views', 'likes', 'tag_count',
										'comments', 'shares', 'flags', 'date_created', 'date_modified', 'tags', 'privateid');
	public $curationid;
	public $userguid;
	public $topicid;
	public $content_type;
	public $contentid;
	public $commentid;
	public $rank;
	public $views;
	public $likes;
	public $tag_count;
	public $comments;
	public $shares;
	public $flags;
	public $date_created;
	public $date_modified;
	public $tags;
	public $privateid;
	
	public $count_curations_for_cell; //count of the # of curations for a knowledge cell
	public $count_people_for_cell; //count the # of people who are curating in this knowledge cell
			
	//class methods		
	
	/*
	SELECT t.topicid, t.topic
FROM topics AS t, curations AS c, content AS n
WHERE GLOBAL =  'Y'
AND t.topicid = c.topicid
AND c.contentid = n.contentid
GROUP BY t.topicid, t.topic
ORDER BY MAX( c.curationid ) DESC 
	*/

	public static function current_jumppads($per_page, $offset, $user_id, $private_user_index_part=0)
		{
		global $database;
		global $session;
		
		$where = '';
		$where2 = '';
		if($session->user_view == 'global' and $session->user_search == '')
			{
			$where2 = " global = 'Y' and ";
			}

		$where = " where $where2 t.privateid = 0 and c.topicid = t.topicid and c.contentid = n.contentid and c.flags < 3 ";
 
		
		if($user_id == 0)
			$user_id_now = $session->user_id;
		else
			$user_id_now = $user_id;
		
		if($session->user_view == 'personal')
			{
			$where .= "and c.userguid = ".$user_id_now." and t.global != 'Y' ";
			}
			
		else if($session->user_view == 'private' and $private_user_index_part != 0)
			{
			$where = ", private_groups_members as m
						where t.privateid > 0 and c.topicid = t.topicid and c.contentid = n.contentid and
						t.privateid = m.privateid and m.userguid = ".$user_id_now." and m.private_user_index_part = ".$private_user_index_part;
			}
		if($session->user_search != '' and strtolower($session->user_search) != 'find knowledge')
			{
			$where .= " and ((c.tags like '%".addslashes($session->user_search)."%') or 
		                (t.topic like '%".addslashes($session->user_search)."%') or
		                (n.title like '%".addslashes($session->user_search)."%') or
		                (n.url like '%".addslashes($session->user_search)."%')) ";
			}
		$sort = '';
		if($session->user_sort == 'Date')
			$sort = " GROUP BY t.topicid, t.topic ORDER BY MAX( c.curationid ) DESC ";
		else if($session->user_sort == 'Alphabetical')
			$sort = " order by t.topic asc";
		else if($session->user_sort == 'Relevance')
			$sort = " order by c.rank desc";
		else if($session->user_sort == 'Content')
			$sort = " order by c.date_created desc";
					
		$topicid_set = array();
		$topic_set = array();
		$topiccount = 0;
		
 		$sql = "select c.topicid, c.curationid, n.contentid, c.rank, c.views, c.likes, c.tag_count, c.comments,
				c.shares, c.flags, c.tags, t.topic, n.url, n.title, t.global, count(t.topic) from topics as t, ".self::$table_name." as c, content as n 
				$where 
				Group by t.topic having count(t.topic) = 1
				$sort";
 		$sql = "select c.topicid, c.curationid, n.contentid, c.rank, c.views, c.likes, c.tag_count, c.comments,
				c.shares, c.flags, c.tags, t.topic, n.url, n.title, t.global, count(t.topic) from ".self::$table_name." as c 
				left join (content as n, topics as t) on (c.contentid = n.contentid and c.topicid = t.topicid)
				$where 
				Group by c.topicid having count(c.topicid) = 1
				$sort";
 		$sql = "SELECT t.topicid, t.topic
				FROM topics AS t, curations AS c, content AS n
				$where
				$sort ";

		$sql .= " LIMIT {$per_page} ";
		$sql .= " OFFSET {$offset}";
		$session->message .= '--Curations(jumppad): '.$sql;
		$result_set = $database->query($sql);
		$object_array = array();
		while($row = $database->fetch_array($result_set))
			{
			$topicid = $row['topicid'];
			$topic = $row['topic'];
			if(!in_array($topicid, $topicid_set) and $topiccount < $session->page_count)
				{
				$topiccount++;
				array_push($topicid_set, $topicid);
				$object_array[] = self::instantiate($row);
				}
			}
		$session->set_topic_array($topicid_set);
		return $object_array;
		
		}

	public static function current_jumppadsold($per_page, $offset, $user_id)
		{
		global $database;
		global $session;
		
		$where = "where t.privateid = 0 and c.topicid = t.topicid and c.contentid = n.contentid and c.flags < 3 ";
 
		
		if($user_id == 0)
			$user_id_now = $session->user_id;
		else
			$user_id_now = $user_id;
		
		if($session->user_view == 'global')
			{
			$where .= "and t.global = 'Y' ";
			}
			
		if($session->user_view == 'personal')
			{
			$where .= "and c.userguid = ".$user_id_now;
			}
			
		else if($session->user_view == 'private')
			{
			$where = ", private_groups_members as m
						where t.privateid > 0 and c.topicid = t.topicid and c.contentid = n.contentid and
						t.privateid = m.privateid and m.userguid = ".$user_id_now;
			}
		if($session->user_search != '' and strtolower($session->user_search) != 'find knowledge')
			{
			$where .= " and ((c.tags like '%".$session->user_search."%') or 
		                (t.topic like '%".$session->user_search."%') or
		                (n.title like '%".$session->user_search."%') or
		                (n.url like '%".$session->user_search."%')) ";
			}
		$sort = '';
		if($session->user_sort == 'Date')
			$sort = " order by c.curationid desc";
		else if($session->user_sort == 'Alphabetical')
			$sort = " order by t.topic asc";
		else if($session->user_sort == 'Relevance')
			$sort = " order by c.rank desc";
		else if($session->user_sort == 'Content')
			$sort = " order by n.date_created desc";
					
		$topicid_set = array();
		$topic_set = array();
		$topiccount = 0;
		
 		$sql = "select c.topicid, c.curationid, n.contentid, c.rank, c.views, c.likes, c.tag_count, c.comments,
				c.shares, c.flags, c.tags, t.topic, n.url, n.title from ".self::$table_name." as c, topics as t, content as n 
				$where  $sort";
		$sql .= " LIMIT {$per_page} ";
		$sql .= " OFFSET {$offset}";
		$session->message .= '--Curations(jumppad): '.$sql;
		$result_set = $database->query($sql);
		$object_array = array();
		while($row = $database->fetch_array($result_set))
			{
			$topicid = $row['topicid'];
			$topic = $row['topic'];
			if(!in_array($topicid, $topicid_set) and $topiccount < $session->page_count)
				{
				$topiccount++;
				array_push($topicid_set, $topicid);
				$object_array[] = self::instantiate($row);
				}
			}
		$session->set_topic_array($topicid_set);
		return $object_array;
		
		}
		
	public static function current_cells($per_page, $offset)
		{
		global $database;
		global $session;
		$where2 = '';
		$where = "where c.topicid = ".$session->user_knocell." and c.flags < 3 ";
		//$where = "where c.topicid = ".$session->user_knocell." and c.flags < 3 ";
		if($session->user_view == 'personal' and 1==2)
			$where .= " and c.userguid = ".$session->user_id." ";
			
		if($session->user_view != 'private')
			$where .= " and c.privateid = 0 ";
			
		if($session->knocell_view == 'Content')
			$where .= " and c.content_type = 0 ";
		else if($session->knocell_view == 'Threads')
			$where .= " and (c.content_type = 1 or c.content_type = 2)";
		else if($session->knocell_view == 'QandA')
			$where .= " and c.content_type = 2 ";
		else if($session->knocell_view == 'Personas')
			$where .= " and c.content_type = 3 ";
			
		if($session->user_search != '' and strtolower($session->user_search) != 'find knowledge')
			{
			$where .= " and (t.url like '%".addslashes($session->user_search)."%' or 
			                 t.title like '%".addslashes($session->user_search)."%' or 
			                 t.source_website like '%".addslashes($session->user_search)."%' or
			                 c.tags like '%".addslashes($session->user_search)."%' ) ";
			$where2 .= " and (c.tags like '%".addslashes($session->user_search)."%' ) ";
			}
		$sort = '';
		if($session->user_sort == 'Date')
			$sort = " order by c.date_modified desc, c.curationid desc";
		else if($session->user_sort == 'Alphabetical')
			$sort = " order by t.title asc";
		else if($session->user_sort == 'Content')
			$sort = " order by t.title asc";
		else if($session->user_sort == 'Relevance')
			$sort = " order by c.rank desc, t.title asc";
			
		$sql = "select c.curationid, c.userguid, c.topicid, c.content_type, c.contentid,
		               c.commentid, c.rank, c.views, c.likes, c.shares, c.flags, count(c.contentid) from ".self::$table_name." as c, content as t $where  
		Group by url having count(c.contentid) = 1 $sort";
		$sql = "select c.curationid, c.userguid, c.topicid, c.content_type, c.contentid,
		               c.commentid, c.rank, c.views, c.likes, c.shares, c.flags, count(c.contentid) from ".self::$table_name." as c $where  
		Group by contentid having count(c.contentid) = 1 $sort";
		$sql = "select c.curationid, c.userguid, c.topicid, c.content_type, c.contentid,
		               c.commentid, c.rank, c.views, c.likes, c.shares, c.flags from ".self::$table_name." as c, content as t $where  
		  $sort";
		$sql = "select c.curationid, c.userguid, c.topicid, c.content_type, c.contentid,
		               c.commentid, c.rank, c.views, c.likes, c.shares, c.flags from ".self::$table_name." as c left join content as t on c.contentid = t.contentid $where
		  $sort";
		$sql .= " LIMIT {$per_page} ";
		$sql .= " OFFSET {$offset}";
		$session->message .= "\n******".$sql."*********";
		
		$topicid_set = array();
		$topiccount = 0;
		$result_set = $database->query($sql);
		$object_array = array();
		while($row = $database->fetch_array($result_set))
			{
			$object_array[] = self::instantiate($row);
			}
		return $object_array;
		
		}

	public static function current_user_cells($per_page, $offset, $userguid=0)
		{
		global $database;
		global $session;
		$where2 = '';
		$where = "where c.userguid = ".$userguid." and c.flags < 3 ";
		if($userguid != $session->user_id)
			$where = $where . ' and c.privateid = 0 ';
						
		if($session->user_search != '' and strtolower($session->user_search) != 'find knowledge')
			{
			$where .= " and (t.url like '%".addslashes($session->user_search)."%' or 
			                 t.title like '%".addslashes($session->user_search)."%' or 
			                 t.source_website like '%".addslashes($session->user_search)."%' or
			                 c.tags like '%".addslashes($session->user_search)."%' ) ";
			$where2 .= " and (c.tags like '%".addslashes($session->user_search)."%' ) ";
			}
		$sort = '';
		if($session->user_sort == 'Date')
			$sort = " order by c.date_modified desc, c.curationid desc";
		else if($session->user_sort == 'Alphabetical')
			$sort = " order by t.title asc";
		else if($session->user_sort == 'Content')
			$sort = " order by t.title asc";
		else if($session->user_sort == 'Relevance')
			$sort = " order by c.rank desc, t.title asc";
			
		$sql = "select c.curationid, c.userguid, c.topicid, c.content_type, c.contentid,
		               c.commentid, c.rank, c.views, c.likes, c.shares, c.flags, count(c.contentid) from ".self::$table_name." as c, content as t $where  
		Group by url having count(c.contentid) = 1 $sort";
		$sql = "select c.curationid, c.userguid, c.topicid, c.content_type, c.contentid,
		               c.commentid, c.rank, c.views, c.likes, c.shares, c.flags, count(c.contentid) from ".self::$table_name." as c $where  
		Group by contentid having count(c.contentid) = 1 $sort";
		$sql = "select c.curationid, c.userguid, c.topicid, c.content_type, c.contentid,
		               c.commentid, c.rank, c.views, c.likes, c.shares, c.flags from ".self::$table_name." as c, content as t $where  
		  $sort";
		$sql = "select c.curationid, c.userguid, c.topicid, c.content_type, c.contentid,
		               c.commentid, c.rank, c.views, c.likes, c.shares, c.flags from ".self::$table_name." as c left join content as t on c.contentid = t.contentid $where
		  $sort";
		$sql .= " LIMIT {$per_page} ";
		$sql .= " OFFSET {$offset}";
		$session->message .= "\n".$sql;
		
		$topicid_set = array();
		$topiccount = 0;
		$result_set = $database->query($sql);
		$object_array = array();
		while($row = $database->fetch_array($result_set))
			{
			$object_array[] = self::instantiate($row);
			}
		return $object_array;
		
		}

	public static function current_cell($userguid)
		{
		global $database;
		global $session;
		$where2 = '';
		$where = "where c.flags < 3 ";

		$where .= " and c.userguid = ".$userguid." ";
			
		$where .= " and c.privateid = 0 "; //don't bring back private curation because person looking at it may not have permission
									
		$sql = "select c.curationid, c.userguid, c.topicid, c.content_type, c.contentid,
		               c.commentid, c.rank, c.views, c.likes, c.shares, c.flags from ".self::$table_name." as c left join content as t on c.contentid = t.contentid $where ";
		$sql .= " order by c.curationid desc LIMIT 1 ";
		$session->message .= "\n".$sql;
		
		$result_array = self::find_by_sql($sql);
		return !empty($result_array) ? array_shift($result_array) : false;
		
		}

	public static function get_user_name($contentid)
		{
		global $database;
		global $session;
		
		$sql  = "select name from ".self::$table_name." as c, users as u where c.userguid = u.userguid and c.contentid = ".$contentid." order by curationid asc limit 1";
		$session->debug = $sql;
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		return array_shift($row); //gets the 1st item in the row
		}	
			
	public static function get_user_id($contentid)
		{
		global $database;
		global $session;
		
		$sql  = "select c.userguid from ".self::$table_name." as c, users as u where c.userguid = u.userguid and c.contentid = ".$contentid." order by curationid asc limit 1";
		$session->debug = $sql;
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		return array_shift($row); //gets the 1st item in the row
		}	
			
	public static function get_thread_id($curationid)
		{
		global $database;
		global $session;
		
		$sql  = "select c.commentid from ".self::$table_name." as c, comments as u where c.commentid = u.comentid and c.curationid = ".$curationid." order by curationid asc limit 1";
		$session->debug = $sql;
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		return array_shift($row); //gets the 1st item in the row
		}	
			
	public static function get_trends($limit)
		{
		global $database;
		global $session;
		
		$sql  = "select distinct topicid from ".self::$table_name." where date_modified != '0000-00-00 00:00:00' order by date_modified limit ".$limit;
		$session->debug = $sql;
		$result_array = self::find_by_sql($sql);
		return !empty($result_array) ? $result_array : false;
		}	
			
	public static function get_tag_trends($limit, $topicid)
		{
		global $database;
		global $session;
		
		$sql = "SELECT * , COUNT( tags ) FROM ".self::$table_name." where topicid = ".$topicid." GROUP BY tags HAVING COUNT( tags ) = 1
                ORDER BY date_modified DESC limit ".$limit;
		$session->debug = $sql;
		$result_array = self::find_by_sql($sql);
		if(count($result_array) < 3)
			{
			$sql = "SELECT * , COUNT( tags ) FROM ".self::$table_name." GROUP BY tags HAVING COUNT( tags ) = 1
					ORDER BY date_modified DESC limit ".$limit;
			$session->debug = $sql;
			$result_array = self::find_by_sql($sql);
			}
		return !empty($result_array) ? $result_array : false;
		}	

	public static function count_tags($tagstrip)
		{
		global $database;
		global $session;
		

		//$sql = "select count(*) from ".self::$table_name." where tags like '%".addslashes($tagstrip)."%' and topicid like ".$session->user_knocell;
		$sql = "select count(*) from ".self::$table_name." where tags like '%".addslashes($tagstrip)."%'";
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		return array_shift($row); //gets the 1st item in the row
		}
		
	public static function insert_curation($contentid, $addTag)
		{
		global $database;
		global $session;
		
		$mydate = date('Y-m-d G:i:s');
	
		$qu_inser_curation = "insert into curations set userguid = ". $session->user_id . ", 
													topicid  = ".$session->user_knocell. ",
													content_type = '0',
													contentid = ".$contentid. ",
													date_created = ".$mydate. ",
													rank = 0,
													views = 1,
													likes = 0,
													tag_count = 1,
													comments = 0,
													shares = 0,
													flags = 0,
													tags = ".addslashes($addTag);
		$resultaddtag = mysql_query($qu_inser_curation);
		}
	
	
	public static function current_jumppads2($per_page, $offset)
		{
		global $database;
		global $session;
		
		$where = "where 1=1 ";
		
		if($session->user_view == 'personal')
			$where .= "and userguid = ".$session->user_id;
		if($session->user_search != '' and strtolower($session->user_search) != 'find knowledge')
			{
			$where .= " and (tags like '%".addslashes($session->user_search)."%') ";
			}
		$sort = '';
		if($session->user_sort == 'date')
			$sort = " order by curationid desc";
		else if($session->user_sort == 'alphabetical')
			$sort = " order by curationid desc";
			
		$sql = "select * from ".self::$table_name." $where  $sort";
		$sql .= " LIMIT {$per_page} ";
		$sql .= " OFFSET {$offset}";
		//$session->message = $sql;
		
		$topicid_set = array();
		$topiccount = 0;
		$result_set = $database->query($sql);
		$object_array = array();
		while($row = $database->fetch_array($result_set))
			{
			$topicid = $row['topicid'];
			if(!in_array($topicid, $topicid_set) and $topiccount < $session->page_count)
				{
				$topiccount++;
				array_push($topicid_set, $topicid);
				$object_array[] = self::instantiate($row);
				}
			}
		return $object_array;
		
		}
	
	public static function count_curations($topicid, $content_type)
		{
		global $database;
		global $session;
		//should get the activity for the last 30 days
		$myearliest = time() - (60*60*24*30);
		$myearliestdate = date('Y-m-d', $myearliest);
		// not used yet
		
		$sql = "select count(*) from ".self::$table_name." where topicid = ".$topicid." and content_type = ".$content_type;
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		return array_shift($row); //gets the 1st item in the row
		}
		
	public static function count_threads($topicid)
		{
		global $database;
		global $session;
		$sql = "select count(*) from curations as c where c.topicid = ".$topicid." and (c.content_type = 1 or c.content_type = 2)";
		$result_set = $database->query($sql); 
		$row = $database->fetch_array($result_set);
		//$session->message .= '<br/><br/>countthreads:'.$sql;
		return array_shift($row); //gets the 1st item in the row
		}
		
	public static function count_jumppads($countview="", $userguid="", $private_user_topic=0)
		{ 
		global $database;
		global $session;
		$sql = "select count(*) from ".self::$table_name;
		if($private_user_topic == 0 or $private_user_topic == '')
			$private_user_topic = 0;
			
		if($countview == 'private')
			{
			if($userguid == '')
				$userguid = $session->user_id;
			$where = " where c.privateid > 0 and c.flags < 3 and c.privateid = pgm.privateid and pgm.userguid = ". $session->user_id;
			$sql = "select count(*) from ".self::$table_name." as c, private_groups as pg, private_groups_members as pgm ".$where;
			$sql = "select count(*) from curations as c, private_groups as p
			where c.privateid = p.privateid and p.private_user_index_part = $private_user_topic and c.privateid > 0 and c.flags < 3 ";
			}
		else if($countview == 'personal')
			{
			if($userguid == "")
				$where = "where userguid = ". $session->user_id." and privateid = 0 ";
			else
				$where = "where userguid = ". $userguid." and privateid = 0";
			$sql = "select count(*) from ".self::$table_name." ".$where;
			}
		else
			$where = " where privateid = 0 and flags < 3 ";
		$session->message .= '<br>count_jumppads: '.$sql;
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		return array_shift($row); //gets the 1st item in the row
		}
		
	public static function count_jumppads_topic($countview="", $userguid="", $topicid=0, $tags="")
		{ 
		global $database;
		global $session;
		$sql = "select count(*) from ".self::$table_name;
		
		$addwhere = "";
		if($tags != '')
			$addwhere = " and tags != '' ";
			
		$addtopicid = "";
		if($topicid != 0)
			$addtopicid = " and topicid = ".$topicid;
			
		if($countview == 'private')
			{
			$where = " where c.topicid = '$topicid' and c.privateid > 0 and c.flags < 3 and c.privateid = pgm.privateid and pgm.userguid = ". $session->user_id;
			$sql = "select count(*) from ".self::$table_name." as c, private_groups as pg, private_groups_members as pgm ".$where.$addwhere;
			$sql = "select count(*) from topics where privateid > 0 ";
			}
		if($countview == 'personal')
			{
			if($userguid == "")
				$where = "where userguid = ". $session->user_id." ".$addtopicid.$addwhere;
			else
				$where = "where userguid = ". $userguid." ".$addtopicid.$addwhere;
			$sql = "select count(*) from ".self::$table_name." ".$where;
			}

		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		return array_shift($row); //gets the 1st item in the row
		}
		
	public static function knowledge_interest($userguid = "", $knowledge = "K", $choice = 1)
		{ 
		global $database;
		global $session;
		
		if($userguid == '')
			return false;
			
		$where = "WHERE userguid =  ". $userguid ." and topicid != 0 and content_type = 0";
		
		if($knowledge == 'K')
			$where = "WHERE userguid =  ". $userguid ." and topicid != 0 ";  // all curations??? content and threads
		else
			$where = "where userguid = ". $userguid ." and topicid != 0 and (content_type = 1 or content_type = 2)";

		$sql = "SELECT topicid, COUNT( topicid ) 
					FROM  ".self::$table_name."
					".$where."
					GROUP BY topicid
					ORDER BY COUNT( topicid ) DESC 
					LIMIT 6";

		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		$session->message .= $row;
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
		
	public static function count_people($topicid)
		{
		global $database;
		global $session;
		$sql = "select distinct userguid from ".self::$table_name." where topicid = ".$topicid;
		$result_set = $database->query($sql);
		return mysql_num_rows($result_set); //gets the 1st item in the row
		}
		
	public static function count_total_knowcells($userguid)
		{
		global $database;
		global $session;
		$sql = "select distinct topicid from ".self::$table_name." where userguid = ".$userguid;
		$result_set = $database->query($sql);
		return mysql_num_rows($result_set); //gets the 1st item in the row
		}
		
	public static function increment_flags()
		{
		global $database;
		global $session;
		$sql = "update ".self::$table_name." set flags = flags + 1 where curationid = ".$session->curationid." limit 1";
		$result_set = $database->query($sql);
		return true; //gets the 1st item in the row
		}
		
	public static function increment_likes()
		{
		global $database;
		global $session;
		$sql = "update ".self::$table_name." set likes = likes + 1, rank = rank + 1 where curationid = ".$session->curationid." limit 1";
		$result_set = $database->query($sql);
		return true; //gets the 1st item in the row
		}
		
	public static function decrement_likes()
		{
		global $database;
		global $session;
		$sql = "update ".self::$table_name." set likes = likes - 1 where curationid = ".$session->curationid." limit 1";
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
		$sql = "select * from ".self::$table_name." where curationid={$database->escape_value($id)} limit 1";
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
		return isset($this->curationid) ? $this->update() : $this->create();
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
			$this->curationid = $database->insert_id();
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
		$sql .= " Where curationid = ". $database->escape_value($this->curationid) ;
		
		$database->query($sql);
		return ($database->affected_rows() == 1) ? true : false;
		}
	
	public function delete()
		{
		global $database;
		
		$sql = "delete from ".self::$table_name;
		$sql .= " where curationid = " . $database->escape_value($this->curationid);
		$sql .= " limit 1";
		
		$database->query($sql);
		return ($database->affected_rows() == 1) ? true : false;
		}
		
	
	}

?>