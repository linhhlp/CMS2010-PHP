<?php
/**
 * @author HLP4ever 2010.
 * @version		2010
 * @package		HLP4ever blog.
 * @copyright	Copyright (C) 2010 by HLP.
 * @license		Open Source and free for non-conmecial
 * This is blog website which is coded in PHP by HLP4ever.
 * Thank you for using it.
 * If it gets a trouble or error, please mail me by email : linh.hlp@gmail.com
 * For documentation, try this link: hlp007.com/code or hlp7.com/code
 * 
 * This is index file.
 * 
 */
//$start_time = microtime(true);

define('START', 'started');
// firstly, running session_start() :
if(!isset($_SESSION['ss'])) {
	session_start();
	$_SESSION['ss'] = 1;
/*
 * This function will help us if our site is attacked!
	$time_restrict = 0.1; // second
if(restrictAccess($time_restrict) == FALSE) return;
*/
	
}

// find config
if(!is_file('config.php')) {
	// RUNNING SET UP TO INSTALL
	if(is_file('install.php')) {
		include_once ('install.php');
	}
	else {
		echo 'Can not find install.php file.';
		exit;
	}
}
else {
	if(is_file('install.php')|| is_dir('install')) {
		echo 'After installation please <font color=red>DELETE or REMOVE</font> INSTALL Folder and install.php file!';
		return;
	}
		// Start website:
		// load config
	include_once 'config.php';
	$_CONFIG = new Config();
		// Include necessary files
	if(is_file(LIB.'/function.php')) {
		include_once (LIB.'/function.php');
	}
	else {
		throw new myException('Can not find function.php file.');
		exit;
	}
	
	if( $_SERVER['SERVER_NAME'] == 'blog.hlpis.me' ) {
		//echo "New HLPis.ME domain";
		redirect("http://blog.hlpis.me".$_SERVER['REQUEST_URI'],"<h1>New <font color='red'>HLPis.ME</font> domain</h1>",3);
		die;
	}
	
	global $login;
	$login = new login();
	$login->checkLogin();
	// remove escape in POST and GET data.
	if(ini_get("magic_quotes_gpc") == 1) {
		removeEscape();
	}
	$_URL	= new urlmanager();
	$_TEMP 	= new template();
	$str = $_TEMP->loadTemp(); 
	echo $str;
//	echo DB_MySql::countqr();
//	$end_time = microtime(true);
//	echo $end_time - $start_time;
}
?>