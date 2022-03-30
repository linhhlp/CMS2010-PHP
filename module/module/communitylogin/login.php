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
// if this is social network, so re-config PREFIX
if(is_file("../communityconfig.php")) {
	if($_SESSION['socialnetwork'][$_POST['prefix']]['privatekey'] == $_POST['privatekey'] )	define(PREFIX ,$_POST['prefix']);
	else return;
}
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
echo "<?xml version='1.0' encoding='UTF-8' ?>"."\n";
echo "<community>";
if($_GET['login'] =='logout') {
	//echo 'Ban dang thoat dang nhap';
    $login = new login();
	$login->logout();
	/*
	echo "<script type='text/javascript'>";
        echo "\n var logged = 0  \n action_login(); \n";
        echo "</script>";
        */
	echo '<code>4</code>';
}
else {
    // Login as a friend
    // First check if user name alread registried ( as a member)
    $login = new login();
    $name   = $_POST['name'];
    $pass   = $_POST['pass'];
	if($_POST['logintype'] != "") {
	   $logintype = $_POST['logintype'];
       $_SESSION['temp_type_user_login_bonus'] = $logintype;  
	}
    else  $logintype = $_SESSION['temp_type_user_login_bonus'] ;  
	if($logintype == "friend") {
		// first, check in friend list
		if(empty($pass)) {
			$community = new community();
			$friend_list = $community->getConfig('makefriend','community','','friend',$_POST['name']);
			print_r($friend_list);
			if(!empty($friend_list)) {
				$_SESSION['temp_name_user_login_bonus'] = $name;
				echo '<code>5</code>';
				foreach($friend_list as $value) {
					$get_info	 	= explode(":-:",$value['value3']);
					echo "<url>".$get_info[2]."</url>";
				}
			}
			else {
				$_SESSION['user'] = $name;
				$_SESSION['type'] = '0';
				echo "<code>0</code><type>".$type_user[$_SESSION['type']].'</type><name>'.$_SESSION['user']."</name>";
			}
		}
		else {
			// send data login to friend's site
			$data = array("name"=>$_SESSION['temp_name_user_login_bonus'],"pass"=>md5($pass));
			$result = sendData($_POST['urlfriend']."xml.php?type=login",$data,"POST","xml");
			if($result->check == "TRUE") {
				$_SESSION['user'] = $_SESSION['temp_name_user_login_bonus'];
				$_SESSION['type'] = '1';
				echo "<code>0</code><type>".$type_user[1].'</type><name>'.$_SESSION['temp_name_user_login_bonus']."</name>";
			}
			else {
				echo '<code>1</code>';
			}
		}
	}
	elseif($logintype == "own") {
		if(empty($pass)) {
			$_SESSION['temp_name_user_login_bonus'] = $name;
			echo '<code>3</code>';	
		}
		else {
			$pass   = md5($pass);
			$name = $_SESSION['temp_name_user_login_bonus'];
			$login = new login();
			$login->checkLogin($name,$pass);
			if($login->getLogged() == TRUE) {
				
				echo "<code>0</code><type>".$type_user[$_SESSION['type']].'</type><name>'.$_SESSION['user']."</name>";
		
			}
			else {
				echo '<code>1</code>';
			}
		}
	}
    
    
}

echo "</community>";
?>