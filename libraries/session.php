<?php
// A class to help work with Sessions
// In our case, primarily to manage logging users in and out

// Keep in mind when working with sessions that it is generally 
// inadvisable to store DB-related objects in sessions

class Session {
	
	private $logged_in = false;
	public $user_id;
	public $message;
	public $debug;
	public $user_view; //global, personal, private
	public $user_search; //last search
	public $user_sort; //last sort
	public $user_knocell; //current knowledge cell
	public $page_count=325; //
	public $per_page=1500; //sql to cover duplicates for page_count
	public $topics;
	public $knocell_view; //all, content, threads, q&a, personas
	public $contentid; //current contentid
	public $curationid; //current contentid
	public $commentid; //current commentid
	public $user_likes_flagsid; //current id for likes on a specific content from a user
	public $trending; //current id for likes on a specific content from a user
	
	function __construct() 
		{
		session_start();
		$this->check_message();
		$this->check_debug();
		$this->check_login();
   		 if($this->logged_in) 
   		 	{
      		// actions to take right away if user is logged in
      		if(isset($_SESSION['user_search']))
      			$this->user_search = $_SESSION['user_search'];
      		else
      			{
      			$this->user_search = '';
      			$_SESSION['user_search'] = '';
      			}
      		if(isset($_SESSION['user_sort']))
      			$this->user_sort = $_SESSION['user_sort'];
      		else
      			{
      			$this->user_sort = 'Date';
      			$_SESSION['user_sort'] = 'Date';
      			}
      		if(isset($_SESSION['user_view']))
      			$this->user_view = $_SESSION['user_view'];
      		else
      			{
      			$this->user_view = 'global';
      			$_SESSION['user_view'] = 'global';
      			}
      		if(isset($_SESSION['user_knocell']))
      			$this->user_knocell = $_SESSION['user_knocell'];
      		else
      			{
      			$this->user_knocell = '103';
      			$_SESSION['user_knocell'] = '103'; //default to art at start
      			}
      		if(isset($_SESSION['topics']))
      			$this->topics = $_SESSION['topics'];
      		else	
      			{
      			$this->topics = '';
      			$_SESSION['topics'] = '';
      			}
      		if(isset($_SESSION['knocell_view']))
      			$this->knocell_view = $_SESSION['knocell_view'];
      		else	
      			{
      			$this->knocell_view = 'All';
      			$_SESSION['knocell_view'] = 'All';
      			}
      		if(isset($_SESSION['contentid']))
      			$this->contentid = $_SESSION['contentid'];
      		else	
      			{
      			$this->contentid = '';
      			$_SESSION['contentid'] = '';
      			}
      		if(isset($_SESSION['curationid']))
      			$this->curationid = $_SESSION['curationid'];
      		else	
      			{
      			$this->curationid = '';
      			$_SESSION['curationid'] = '';
      			}
      		if(isset($_SESSION['trending']))
      			$this->trending = $_SESSION['trending'];
      		else	
      			{
      			$this->trending = 'content';
      			$_SESSION['trending'] = 'content';
      			}
      		if(isset($_SESSION['commentid']))
      			$this->commentid = $_SESSION['commentid'];
      		else	
      			{
      			$this->commentid = 0;
      			$_SESSION['commentid'] = 0;
      			}
    		} 
    	else 
    		{
      		// actions to take right away if user is not logged in
   			}
		}
	
  public function is_logged_in() {
    return $this->logged_in;
  }

  public function user_last_search() {
    return $this->user_search;
  }

  public function user_last_sort() {
    return $this->user_sort;
  }

  public function user_last_view() {
    return $this->user_view;
  }

  public function user_last_knocell() {
    return $this->user_knocell;
  }

	public function login($user) {
    // database should find user based on username/password
    if($user){
      $this->user_id = $_SESSION['user_id'] = $user->userguid;
      $this->logged_in = true;
    }
  }
  
  public function logout() {
    unset($_SESSION['user_id']);
    unset($this->user_id);
    $this->logged_in = false;
  }

	public function message($msg="") {
	  if(!empty($msg)) {
	    // then this is "set message"
	    // make sure you understand why $this->message=$msg wouldn't work
	    $_SESSION['message'] = $msg;
	  } else {
	    // then this is "get message"
			return $this->message;
	  }
	}

	public function debug($msg="") {
	  if(!empty($msg)) {
	    // then this is "set debug"
	    // make sure you understand why $this->debug=$msg wouldn't work
	    $_SESSION['debug'] = $msg;
	  } else {
	    // then this is "get debug"
			return $this->debug;
	  }
	}

	private function check_login() {
    if(isset($_SESSION['user_id'])) {
      $this->user_id = $_SESSION['user_id'];
      $this->logged_in = true;
    } else {
      unset($this->user_id);
      $this->logged_in = false;
    }
  }
  
	private function check_message() {
		// Is there a message stored in the session?
		if(isset($_SESSION['message'])) {
			// Add it as an attribute and erase the stored version
      $this->message = $_SESSION['message'];
      unset($_SESSION['message']);
    } else {
      $this->message = "";
    }
	}
	
	private function check_debug() {
		// Is there a debug stored in the session?
		if(isset($_SESSION['debug'])) {
			// Add it as an attribute and erase the stored version
      $this->debug = $_SESSION['debug'];
      unset($_SESSION['debug']);
    } else {
      $this->debug = "";
    }
	}
	
	public function set_user_view($user_view) 
		{
    	// keep track of user view session: global, personal, private
    	if(isset($user_view))
    		{
      		$this->user_view = $_SESSION['user_view'] = $user_view;
      		return true;
    		}
  		}
  
	public function set_user_search($user_search) 
		{
    	// keep track of user last search
    	if(isset($user_search))
    		{
      		$this->user_search = $_SESSION['user_search'] = $user_search;
      		return true;
    		}
  		}
  
	public function set_user_sort($user_sort) 
		{
    	// keep track of user last sort
    	if(isset($user_sort))
    		{
      		$this->user_sort = $_SESSION['user_sort'] = $user_sort;
      		return true;
    		}
  		}
  
	public function set_user_knocell($user_knocell) 
		{
    	// keep track of user knocell (topicid in the topics table)
    	if(isset($user_knocell))
    		{
      		$this->user_knocell = $_SESSION['user_knocell'] = $user_knocell;
      		return true;
    		}
  		}
  		
	public function set_curationid($curationid) 
		{
    	// keep track of curationid
    	if(isset($curationid))
    		{
      		$this->curationid = $_SESSION['curationid'] = $curationid;
      		return true;
    		}
  		}
  		
	public function set_contentid($contentid) 
		{
    	// keep track of curationid
    	if(isset($contentid))
    		{
      		$this->contentid = $_SESSION['contentid'] = $contentid;
      		return true;
    		}
  		}
  		
	public function set_commentid($commentid) 
		{
    	// keep track of commentid
    	if(isset($commentid))
    		{
      		$this->commentid = $_SESSION['commentid'] = $commentid;
      		return true;
    		}
  		}
  		
	public function set_user_likes_flagsid($user_likes_flagsid) 
		{
    	// keep track of current user likes flag for content or comment
    	if(isset($user_likes_flagsid))
    		{
      		$this->user_likes_flagsid = $_SESSION['user_likes_flagsid'] = $user_likes_flagsid;
      		return true;
    		}
  		}
  		
	public function set_trending($trending) 
		{
    	// keep track of current user likes flag for content or comment
    	if(isset($trending))
    		{
      		$this->trending = $_SESSION['trending'] = $trending;
      		return true;
    		}
  		}
  		
  	public function set_topic_array($topics)
  		{
    	if(isset($topics))
    		{
      		$this->topics = $_SESSION['topics'] = $topics;
      		return true;
    		}
  		}
  		
  	public function set_knocell_view($knocell_view)
  		{
    	if(isset($knocell_view))
    		{
      		$this->knocell_view = $_SESSION['knocell_view'] = $knocell_view;
      		return true;
    		}
  		}
	
}

$session = new Session();
$message = $session->message();

?>