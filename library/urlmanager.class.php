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
 *  This Class for Manager to call Object to process URL 
 *  which will process link and return paramaters of request.
 *  
 */

class urlmanager {
	private static $url;
	function __construct(){
		if(self::$url == '') {
 		global $_CONFIG;
		$type 	= $_CONFIG->getURL();
		self::$url	= new $type['TYPE'];
		}
	}
	function __call($method, $args){
	    if (empty(self::$url)) return 0;
	    if (!method_exists($this, $method))
	    return call_user_func_array(array(self::$url,$method),$args);
	}
	static function makeLink($para1,$para2,$para3 ,$linkdep = "",$text = "", $properties = "") {
		return self::$url->makeLink ($para1,$para2,$para3 ,$linkdep ,$text, $properties);
	}	
}
?>