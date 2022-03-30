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
 * configuration for website
 * Do not change code below, only set up your config if you're sure you've known.
 */
class config {
	private $_CONFIG;
	private static $started = 0;
	function __construct() {
		if(self::$started == 0) {
				/**
				 *  This is configuration, You can change it if when there are some changes.
				 *  
				 *   First is Database config:
				 */
		$this->_CONFIG['DataBase']['HOST'] 		= "localhost";
		$this->_CONFIG['DataBase']['TYPE'] 		= "MySQLi";
		$this->_CONFIG['DataBase']['NAME'] 		= "";
		$this->_CONFIG['DataBase']['USER'] 		= "";
		$this->_CONFIG['DataBase']['PASS'] 		= "";
		$this->_CONFIG['DataBase']['PREFIX'] 	= "hlpblog13_";
		$this->_CONFIG['DataBase']['AUTOCHECK'] = FALSE;
				/**
				 *  URL config.
				 *  Friendly link config.
				 */
		$this->_CONFIG['UrlConfig']['URL'] 		= "http://hlp007.com/";		// Base URL for web
		$this->_CONFIG['UrlConfig']['LINK']		= 'FRIENDLY'; // FRIENDLY : beautiful link as viewtopic.html || NORMAL : as ?hlp=blog
		$this->_CONFIG['UrlConfig']['TYPE']		= 'urlslash';		// Type of separation between link as:  blog/view/15

				/**
				 *  Website config
				 */
		$this->_CONFIG['SiteConfig']['TITLE']	 	 = "HLP4ever - Hoang Trong Linh blog";		// Set title for website page
		$this->_CONFIG['SiteConfig']['LOGIN_TYPE']	 = "db";		// Set title for website page
		$this->_CONFIG['SiteConfig']['LOGIN_TIME']	 = 1200;			// expire time for login
		$this->_CONFIG['SiteConfig']['NOTIFY_TYPE']	 = "mailphp";		// Type of notice : mailphp for mail() PHP function 
		$this->_CONFIG['SiteConfig']['ADMIN_EMAIL']	 = "linhhlp@gmail.com";		// Admin email
		$this->_CONFIG['SiteConfig']['MOBILE']		 = FALSE;
		$this->_CONFIG['SiteConfig']['UPDATESERVER'] = "http://update.hlp007.com/"; 	// address server to check and download update
		date_default_timezone_set('Asia/Ho_Chi_Minh');					// Set timezone for web and database.	// VN: Asia/Ho_Chi_Minh
		
				/**
				 * Community settings
				 */
		$this->_CONFIG['Community']['ENABLE'] = TRUE ; 		// allow other people to connect own site
		
				/**
				 * 
				 *  CAREFULLY: This is advanced settings.
				 */
		// ******************************************* //
				/** 
				 *  Define working folders
				 */
		define('ADM' , "admin");
		define('LIB' , "library");
		define('FILE' , "file");
		define('TEMP' , "template");
		define('IMA' , "images");
		define('MOD' , "module");
        define('COM' , "component");
        define('PLUG' , "plugin");
		define('STUP' , "startup");	
		define('TMP' , "temp");

				/**
				 * Define some constants.
				 */
		// This is URL uses for exact link if you use friendly link. 
		if($_SESSION['makeup_admin_site'] != 'admin') 
			define('_URL' , 'http://'.$_SERVER['SERVER_NAME'].substr($_SERVER["SCRIPT_NAME"],0,strrpos($_SERVER["SCRIPT_NAME"],"/")+1));			
		else define('_URL' , 'http://'.$_SERVER['SERVER_NAME'].substr($_SERVER["SCRIPT_NAME"],0,strrpos($_SERVER["SCRIPT_NAME"],ADM)));
		// Prefix of table in your Database. WARNING: Do not change if you not sure
		define('PREFIX' , $this->_CONFIG['DataBase']['PREFIX']);		
		
		
				/**
				 *  Stop configuring.
				 *  Below is program process config.
				 *  So change it only you're sure. 
				 */
		// Permission:
		$this->_CONFIG['Permission'][0] = 'Guest';
		$this->_CONFIG['Permission'][1] = 'Friend';
		$this->_CONFIG['Permission'][2] = 'Special';
		$this->_CONFIG['Permission'][3] = 'Admin';
		$this->_CONFIG['Permission'][4] = 'Superadmin';
		
		// Loading default setting:
		if(empty($_SESSION['type']))$_SESSION['type'] = "0";
		ini_set("display_errors",0);
		//if(empty($_SESSION['type']))$_SESSION['type'] = 0;
		// Setting completed!
		self::$started = 1; // marked the end of loading config
		}
		
	}
	function getDB() {
		return $this->_CONFIG['DataBase'];
	}
	function getSite() {
		return $this->_CONFIG['SiteConfig'];
	}
	function getURL() {
		return $this->_CONFIG['UrlConfig'];
	}
	function getBlog() {
		return $this->_CONFIG['BlogConfig'];
	}
	function getPermission() {
		return $this->_CONFIG['Permission'];
	}
	function getCommunity() {
		return $this->_CONFIG['Community'];
	}
}
?>