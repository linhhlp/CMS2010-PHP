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
 *  This class will process link and return paramaters of request.
 *  Form of Link is using '/' to separate between parameters.
 *  For example :  hlp007.com/blog/viewblog/2009/all.html
 *  You need/can re-config in .htacess
 *  
 */

class urlslash extends url {
	private  $_old_url;
	private  $_new_url = array();
	private  $_key_url;
	public function  __construct () {		
		 global $_CONFIG;
		 $this->_old_url =  'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		 if(is_array($_GET)) {
		 	$url = $_CONFIG->getURL();		 	
		 	if($url['LINK'] =='FRIENDLY') {
		 		foreach ($_GET as $value) {
		 			$new_arr = explode('/',$value);
		 			foreach ($new_arr as $value2) {
		 				array_push($this->_new_url,$value2);
		 			}		 			
		 		}
		 	}
		 	else {
		 		/*
		 		$this->_key_url = array_keys($_GET);
		 		$_new_url = array();
		 		foreach ($this->_key_url as $value) {
		 			array_push($this->_new_url,$_GET[$value]);
		 		}
		 		*/
		 		$this->_new_url = explode('/',$_GET['hlp']);
		 	}
		 }

	}
	function getOldUrl() {
		return $this->_old_url;	
	}
	function getNewUrl() {
		return $this->_new_url;
	}
	function getKeyUrl() {
		return $this->_key_url;
	}
	static function makeLink($para1,$para2,$para3 ,$linkdep = "",$text = "", $properties = "") {
	/**
	 *  This function will make normal link or friendly link follow config.
	 */
		$_url = substr('http://'.$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'],0,strrpos('http://'.$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'],'/') +1 );
		
		global $_CONFIG;
	$type = $_CONFIG->getURL();
	if($para2 == '')	$para2 = '0';
	if($para3 == '')	$para3 = '0';
	if($type['LINK'] =="FRIENDLY" ) {
		//  Form of link 
		//  hlp007.com/blog/0/114/link_dep.html
		if($linkdep == '') 		$linkdep = "hlp" ;
		$linkdep 	= filter_str($linkdep);
		$href 		= $_url."$para1/$para2/$para3/{$linkdep}.hlp2010.html";
		if($text == "") 	$link 	= $href;
		else $link = "<a href='".$href."' $properties >$text</a>";
	}
	else {
		$href 		= $_url."?hlp=$para1/$para2/$para3";
		if($text == "") 	$link 	= $href;
		else $link = "<a href='".$href."' $properties >$text</a>";
	}
	return	$link;
	}
	
	
}

?>