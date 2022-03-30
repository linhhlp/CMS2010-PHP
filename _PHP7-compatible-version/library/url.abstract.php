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
 *  This Abstract class for extend class to process 
 *  will process link and return paramaters of request.
 *  It is config type of URL
 *  You can re-config in .htacess
 *  
 */

abstract class url {
	private  $_old_url;
	private  $_new_url = array();
	private  $_key_url;
	public function  __construct () {
		/**
		 *  Process the url, split it to parameter array.
		 */
		return 0;
	}
	function getOldUrl() {
		// return origin url
		return $this->_old_url;	
	}
	function getNewUrl() {
		// return an array containt parameter
		return $this->_new_url;
	}
	function getKeyUrl() {
		// return key of parameter, it means that is the key of GET
		return $this->_key_url;
	}
	static function makeLink($para1,$para2,$para3 ,$linkdep = "",$text = "", $properties = "") {
		return 0;
	}
	
}

?>