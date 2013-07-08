<?php

/**
 * Basic Database Model
 * derived from MVC-Framework by Max Weller<max.weller@teamwiki.net> and Moritz Willig <moritz.willig@gmail.com>
 * 
 * @author    Moritz Willig <moritz.willig@gmail.com>
 * 2013_04_07 Complete rewrite of the old class. Uniform code structure. Added comments to all functions.
 * 2013_07_07 Changed initilization of sql connection parameters
**/

class DatabaseModel extends Model {
	
  //connection parameters
	private $host;
	private $user;
	private $passwd;
	private $dbname;
  private $salt;
  
  //connection status
	public $handle;
  public $is_connected = false;
  
  //last query
  public $lastQuery;
	public $lastResult;
  
  /**
   * Sets up the SQLModel and by default opens an MySQL connection
  **/
	function __construct($try_connect=true) {
		parent::__construct();
    
    $db = GlobalConsts::get("DB");
    if ($db) {
      $this->host  =$db["Host"];
      $this->user  =$db["User"];
      $this->passwd=$db["Password"];
      $this->dbname=$db["Database"];
      $this->salt  =$db["Salt"];
    }
    
    if ($try_connect) {
      if (!$this->connect()) { throw new exception("DB Error"); }
    }
	}
  
  /**
   * Cleans up the object and closes opened connections
  **/
  function __destruct() {
    if ($this->is_connected==true) {
      $this->is_connected=false;
      $this->close();
    }
  }
	
  /**
   * Tries to establish a connection to the specified Database
  **/
	function connect() {
		$this->handle=@mysql_connect($this->host,$this->user,$this->passwd);
		if (!$this->handle) {
			return false;
		} else {
			$dbh=mysql_select_db ($this->dbname, $this->handle);
			if (!$dbh) { 
				return false; 
			} else {
        $this->is_connected=true;
        mysql_set_charset('utf8', $this->handle);
				return $this->handle;
			}
		}
	}
  
  /**
   * Closes an open connection
  **/
	function close() {
		mysql_close($this->handle);
	}
  
  /**
   * Creates an injection save sql query.
  **/
  function set_query() {
    $args=func_get_args();
    $max =func_num_args();
    if ($max<2) {
      $this->query = $args[0];
    } else {
      $q=$args[0]; $params=array();
      for($i=1; $i<$max; $i++) $params[] = $this->escape($args[$i]);
      $this->query = vsprintf($q, $params);
    }
  }
  
  /**
   * Executes the currrent query
  **/
  function execute() {
    if (func_num_args()>0) { call_user_func_array(array($this,"set_query"),func_get_args()); }
    
    $this->query();
  }
	
  /**
   * Sends an query to the Database and saves the result to lastResult
  **/
	function query() {
    if (func_num_args()>0) { call_user_func_array(array($this,"set_query"),func_get_args()); }
    
		$this->lastResult=mysql_query($this->query);
    if (!$this->lastResult) { $this->printError(); return false; }
		return true;
	}
  
  /**
   * Returns the current result line as indexed array
  **/
	function get_as_array() {
		return mysql_fetch_array($this->lastResult);
	}

  /**
   * Returns the current result line as an array
  **/
	function get_as_assoc() {
		return mysql_fetch_assoc($this->lastResult);
	}
  
  /**
   * Returns the current result line as associative array
  **/
  function get_next_row() {
    return mysql_fetch_row($this->lastResult);
  }
  
  /**
   * Returns the number of rows in the current query
  **/
  function get_rows_count() {
    return mysql_num_rows($this->lastResult);
  }
  
  /**
   * Returns the number of rows that were effected by the last query
  **/
  function get_affected_rows() {
    return mysql_affected_rows($this->handle);
  }
  
  /**
   * Returns the first row of an query result
  **/
  function get_first_row() {  
    if (call_user_func_array(array($this,"query"),func_get_args())) {
      $row = mysql_fetch_assoc($this->lastResult);
      mysql_free_result($this->lastResult);
        
      return $row;
    } else {
      return null;
    }
  }
  
  /**
   * Returns the first value of the first row in the query result
  **/
  function get_first_value() {
    if (call_user_func_array(array($this,"query"),func_get_args())) {
      $row = mysql_fetch_array($this->lastResult); 
      mysql_free_result($this->lastResult);
      
      if ($row) {
        return $row[0];
      } else {
        return null;
      }
    } else {
      return null;
    }
  }
  
  /**
   * Returns an array with the complete query result
  **/
  function get_all_rows() {
    if (call_user_func_array(array($this,"query"),func_get_args())) {
      $items = array();
      while ($row = mysql_fetch_assoc($this->lastResult)) {
        $items[] = $row;
      }
      mysql_free_result($this->lastResult);
        
      return $items;
    } else {
      return null;
    }
  }
  
  function get_last_inserted_id() {
    return mysql_insert_id ();
  }
  
  /**
   * Function for printing MySQL errors
  **/
  function printError() {
    printf("Es ist ein Datenbankfehler aufgetreten!<br>[%s] %s", mysql_errno($this->handle), mysql_error($this->handle));
  }
  
  /**
   * Creates a salted sha256 hash from a username and password
  **/
	function hash($username, $password) {
		$str = hash("sha256",$this->salt.$username."@".$this->salt.$password.'|+456'.$this->salt.$password);
		return $str;
	}
  
  /**
   * Escapes an string for save use in MySQL queries
  **/
  function escape($str) {
    return mysql_real_escape_string($str, $this->handle);
  }
	
}

?>