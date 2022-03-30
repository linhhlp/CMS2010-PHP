<?php
/**
 * @author HLP4ever .
 * @version		2010
 * @package		HLP4ever blog.
 * @copyright	Copyright (C) 2010 by HLP.
 * @license		Open Source and free for non-conmecial
 */
// No direct access
defined('START') or die;

/**
 * This class helps us to collect MySQL Database and processes database.
 * It is extended from Abstract class : DB
 * So if you want to develop new DB driver, see DB.abstract.php for more information.
 *
 */
define ("FETCH_ASSOC",1);
define ("FETCH_ROW",2);
define ("FETCH_BOTH",3);
define ("FETCH_OBJECT",4);

class DB_MySqli extends DB {
	private $results;
	private $key;
	private static $conn;
	private static $connected;
	private static $count = 0;
	private static $queries = "";
	function __construct() {
		if (!is_resource( self::$conn)) {
			// Connect to DB
			global $_CONFIG;
			$DBtype = $_CONFIG->getDB ();
			self::$conn = mysqli_connect ( $DBtype ['HOST'], $DBtype ['USER'], $DBtype ['PASS'],$DBtype ['NAME'] );
			
			if (! self::$conn) {
				echo 'Loi ket noi ';
			}
		} 
	}
	function escapeString($query) {
		if (function_exists ( 'mysqli_real_escape_string' )) {
			return mysqli_real_escape_string ( self::$conn, $query );
		} elseif (function_exists ( 'mysqli_escape_string' )) {
			return mysqli_escape_string ( self::$conn,$query );
		} else {
			return addslashes ( $query );
		}
	}
	function execute($query) {
		$start_time = microtime(true);
		self::$count++;// .='<br />'.$query;
		$part = explode ( ' ', $query, 1 );
		$part ['0'] = strtolower ( $part ['0'] );
		if ($part ['0'] == 'select') {
			$this->key = md5 ( $query );
			if (isset ( $this->results [$this->key] )) {
				return $this->results [$this->key];
			}
		} elseif ($part ['0'] == 'update' || $part ['0'] == 'delete') {
			$this->results = array();	//reset.
		}
		
		$this->results[$this->key] = mysqli_query(self::$conn, $query);
		//var_dump($this->results[$this->key]);
		//echo "Debugging errno: " . mysqli_error(self::$conn) ;
		$end_time = microtime(true);
		$t1 = ($end_time - $start_time);
		self::$queries .= '['.self::$count.']'.$query.'['.$t1.']'.'<br />';
		return $this->results[$this->key];
	}
	public function count() {
		$lastresult = $this->results [$this->key];
		if(!($lastresult)) $count =  0;
		else $count = $lastresult->num_rows;
		return $count;
	}
	public function results() {
		return $this->results[$this->key];
	}
	public function FetchResult($resource,$fetchmode = FETCH_ASSOC) {
		/*
		 * This function help us fetch Result outsides this class 
		 *     Other word, You will need it when you have Nest Query Loop
		 */
		if(!($resource)) {
			$row = FALSE;
		}
		else {
			$row =  $resource->fetch_array() ;
		}
		return $row;
	}
	public function prepQuery($query) {
		// "DELETE FROM TABLE" returns 0 affected rows.
		// This hack modifies the query so that
		// it returns the number of affected rows
		if (preg_match ( '/^\s*DELETE\s+FROM\s+(\S+)\s*$/i', $query )) {
			//$query = preg_replace ( "/^\s*DELETE\s+FROM\s+(\S+)\s*$", "DELETE FROM \\1 WHERE 1=1", $query );
		}
		return $query;
	}
	public function getRow($fetchmode = FETCH_ASSOC) {
		$lastresult = $this->results [$this->key];
		if(!($lastresult)) {
			$row = FALSE;
		}
		else {
			$row = $lastresult->fetch_array();
			//echo mysql_error();
		}
		return $row;
	}

	function getAffectedRow() {
		return @mysqli_affected_rows ( self::$conn );
	}
	
	public function insertId() {
		return @mysqli_insert_id ( self::$conn );
	}
	public function rewind() {
		$lastresult = $this->results [$this->key];
		mysqli_data_seek ( $lastresult, 0 );
	}
	public function errno ($conn ='') {
		if(empty($conn)) $conn = self::$conn;
		return mysqli_errno($conn);
	}
	public function error ($conn ='') {
		if(empty($conn)) $conn = self::$conn;
		return mysqli_error($conn);
	}
	function __destruct() {
		if(!empty($this->results)) {
			foreach($this->results as $value) {
				@mysqli_free_result ( $value );
			}
		}
	}
	public static function countqr() {
		return self::$count;
	}
	public static function getqueries() {
		return self::$queries;
	}
}

?>