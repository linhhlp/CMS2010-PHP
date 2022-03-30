<?php
/**
 * @author HLP4ever .
 * @version		2010
 * @package		HLP4ever blog.
 * @copyright	Copyright (C) 2010 by HLP.
 * @license		Open Source and free for non-conmecial
 */
// Start define loading
define('START', 'userbonus');
//******************************************
// firstly, running session_start() :
if(!isset($_SESSION['ss'])) {
	session_start();
	$_SESSION['ss'] = 1;
/*
 * This function will help us if our site is attacked!
 */
	function restrictAccess($time_restrict = 0.1) {
		if($_SESSION['restrict_access_1'] == '') {
			$_SESSION['restrict_access_1'] =  microtime(true);
			return TRUE;
		}
		else {
			$_SESSION['restrict_access_2'] =  microtime(true);
			if($_SESSION['restrict_access_2']- $_SESSION['restrict_access_1'] > $time_restrict) {
				$_SESSION['restrict_access_1'] =  microtime(true);
				return TRUE;
			}
			else {
				$_SESSION['restrict_access_1'] =  microtime(true);
				return FALSE;
			}
			
		}
	}	
	$time_restrict = 0.1; // second
	if(restrictAccess($time_restrict) == FALSE) return;
}
// change the working directory (from module dir to root dir)
chdir(dirname(dirname( getcwd())));
// This return the link of this file (for example /module/userbonus/login.php)
$old_cwd = $_SERVER['PHP_SELF'];

//echo getcwd();
//echo $old_cwd;
//load config
include_once 'config.php';
$_CONFIG = new Config();
$type_user = $_CONFIG->getPermission();
	// Include necessary files
if(is_file(LIB.'/function.php')) {
	include_once (LIB.'/function.php');
    
}
//*****************************************
// Starting
if($_GET['action'] == 'checknew' && $_SESSION['new_noticed'] == FALSE && !empty($_SESSION['info']['id']) ) {
	$userbounus = new userbonus();
	$num_mess = $userbounus->isNew($_SESSION['info']['id']);
	if($num_mess > 0) {
		echo "\n Bạn có: ".$num_mess." tin nhắn mới!";
	}
	else echo "";
	$_SESSION['new_noticed'] = TRUE;
}


?>