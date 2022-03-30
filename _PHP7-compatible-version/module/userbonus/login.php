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

/**
* This will check for login
*
*/
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
if($_GET['login'] =='logout') {
	//echo 'Ban dang thoat dang nhap';
    $login = new login();
	$login->logout();
	/*
	echo "<script type='text/javascript'>";
        echo "\n var logged = 0  \n action_login(); \n";
        echo "</script>";
        */
	echo 'code_return_4';
}
else {
    // Login as a friend
    // First check if user name alread registried ( as a member)
    $login = new login();
    $name   = $_POST['name'];
    $pass   = $_POST['pass'];
    if(!empty($pass)) {
	    $pass   = md5($pass);
	    $name = $_SESSION['temp_name_user_login_bonus'];
	    $login = new login();
		$login->checkLogin($name,$pass);
	    if($login->getLogged() == TRUE) {
	 		
	        echo $type_user[$_SESSION['type']].': '.$_SESSION['user'];
	
	    }
	    else {
	        echo 'code_return_1';
	    }
    	
    }
    elseif($login->checkUserExist($name) == TRUE) {
    	$_SESSION['temp_name_user_login_bonus'] = $name;
    	echo 'code_return_3';
    }
    else {
        $_SESSION['user'] = $name;
        $_SESSION['type'] = '0';
        echo $type_user[$_SESSION['type']].': '.$_SESSION['user'];
    }
}


?>