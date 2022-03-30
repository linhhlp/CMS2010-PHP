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



abstract class DB {
	private 		$results;
	private 		$key;
	private static  $connected;
	
	function __construct() {
		if(!self::$connected) {
			// Connect to DB
			global $_CONFIG;
			
		}
		else {
			return self::$connected;
		}
	}
	function escapeString() {
		// remove special character	in query string
	}
	function execute($query) {
		// query the string
	}
	function getRows($start, $count, $fetchmode){	
		// get $count rows from $start  
	}
	public function getRow($fetchmode) {
			// get only a (first) row 
	}
	public function count() {
		// get number of result
	}
	public function results() {
		// get the result (resource type)
	}
	public function FetchResult($resource,$fetchmode) {
		// convert resource type to array (string) type
	}
	public function insertId() {
		// get the ID which has just been inserted
	}
	public function errno ($conn ='') {
		// get error number
	}
	public function error ($conn ='') {
		// get error string
	}

	function queryString($query) {
	}
	function prepare($query) {
	}
}

?>