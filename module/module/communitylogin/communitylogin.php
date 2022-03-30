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

}
// change the working directory (from module dir to root dir)
chdir(dirname(dirname( getcwd())));
// This return the link of this file (for example /module/userbonus/login.php)
$old_cwd = $_SERVER['PHP_SELF'];

//echo getcwd();
//echo $old_cwd;
//load config
$path = $_SERVER["SCRIPT_NAME"];
$path = substr($path,0,strrpos($path,"/"));
$path = substr($path,0,strrpos($path,"/"));
$path = substr($path,0,strrpos($path,"/")+1);
define(_URL , 'http://'.$_SERVER['SERVER_NAME'].($_SERVER["SERVER_PORT"] != 80?$_SERVER["SERVER_PORT"]:'').$path);
include_once 'config.php';
$_CONFIG = new Config();
$type_user = $_CONFIG->getPermission();
	// Include necessary files
if(is_file(LIB.'/function.php')) {
	include_once (LIB.'/function.php'); 
}
$login = new login();
$login->checkLogin();
$user_info = $login->getInfo();
//*****************************************
// Starting
if($_GET['action'] == 'checknew' && !empty($user_info['user_id']) ) {
	if($_SESSION['new_noticed'] == FALSE || $_GET['type'] == 'renew') {
		$str  = "<?xml version='1.0' encoding='UTF-8' ?>"."\n";
		$str .= "<community>";
		$userbounus = new userbonus();
		$new_messages = $userbounus->isNew($user_info['user_id']);
		if($new_messages != FALSE ) {
			$new_messages_num = count($new_messages);
			for($i=0;$i<$new_messages_num;$i++) {
				$value = $new_messages[$i];
				$new_messages_cont .= '<item>';
				$new_messages_cont .= '<id>'.$value['id'].'</id>';
				$new_messages_cont .= '<title>'.htmlspecialchars($value['title']).'</title>';
				$new_messages_cont .= '<content>'.htmlspecialchars($value['content']).'</content>';
				$new_messages_cont .= '<created_by>'.htmlspecialchars($value['created_by']).'</created_by>';
				$new_messages_cont .= '<created>'.$value['created'].'</created>';
				$new_messages_cont .= '</item>';
			}
		}
		else $new_messages_num = 0;
		$str .= "<message>".$new_messages_num."</message>";
		$str .= $new_messages_cont;
		$str .= $userbounus->commentNew();
		//$str .= $list_friend;
		$str .= "</community>";
		$_SESSION['new_noticed'] = $str;
	}
	else $str = $_SESSION['new_noticed'];
	echo $str;
}
elseif($_GET['action'] == 'checkfriendnews' && !empty($user_info['user_id']) ) {
	$userbounus = new userbonus();
	if($_GET['type'] == 'renew')	{ $_SESSION['news_friend_noticed']['value'] = ''; $_SESSION['news_friend_noticed']['done'] = FALSE;}
	if($_SESSION['news_friend_noticed']['done'] == FALSE ) {
		if(isset($_GET['from']) && isset($_GET['step']))	$str = $userbounus->returnFriendList(array($_GET['from'],$_GET['step']));
		else $str = $userbounus->returnFriendList();
		if($str == '') return '';
	}
	else {
		$str = $_SESSION['news_friend_noticed']['value'];
	}
	$str  = "<?xml version='1.0' encoding='UTF-8' ?>"."\n<community>".$str. "</community>";
	echo $str;
}
elseif($_GET['login'] =='logout') {
	echo "<?xml version='1.0' encoding='UTF-8' ?>"."\n";
	echo "<community>";
	//echo 'Ban dang thoat dang nhap';

	$login->logout();
	/*
	echo "<script type='text/javascript'>";
        echo "\n var logged = 0  \n action_login(); \n";
        echo "</script>";
        */
	echo '<code>4</code>';
	echo "</community>";
}
elseif($_GET["type"] == "postnew") {
	echo "<?xml version='1.0' encoding='UTF-8' ?>"."\n";echo "<community>";
	$community = new community();
	$strxml = $community->postnew($_POST['friendname'],$_POST['friendurl'],$_POST['type'],$_POST['title'],$_POST['content'],$_POST['id']);
	$xml = simplexml_load_string($strxml);
	echo $strxml;
	if($xml->completed == 'true') echo '<post>true</post>';
	else echo '<post>false</post>';
	echo "</community>"; 
}
else{		//if($_POST['pass'] !='' || $_POST['name'] != '') 
	echo "<?xml version='1.0' encoding='UTF-8' ?>"."\n";
	echo "<community>";
	
    // Login as a friend
    // First check if user name alread registried ( as a member)
	if($_POST['logintype'] != "") {
	   $logintype = $_POST['logintype'];
       $_SESSION['temp_type_user_login_bonus'] = $logintype;  
	}
    else  $logintype = $_SESSION['temp_type_user_login_bonus'] ;  
	if($logintype == "friend") {
		$name   = $_POST['name'];
		$login->checkLogin();
		if($login->getLogged() == TRUE) {
			$user_info = $login->getInfo();
			if(  $user_info['user_id'] > 0) { $type_user_display = $type_user[$user_info['user_type']];$code = 0;}
			else { $type_user_display = $user_info['user_id']; $code = 2;}
			echo "<code>".$code."</code><type>".$type_user_display.'</type><name>'.$user_info['user_name']."</name></community>";
			die;
		}
		// first, check in friend list
		if( !empty($name)) {
			$community = new community();
			$friend_list = $community->getConfig('makefriend','community','','friend',$_POST['name']);
			//print_r($friend_list);
			if(!empty($friend_list)) {
				$_SESSION['temp_name_user_login_bonus'] = $name;
				echo '<code>5</code><name>'.$name.'</name>';
				foreach($friend_list as $value) {
					$get_info	 	= explode(":-:",$value['value3']);
					echo "<url>".$get_info[2]."</url>";
				}
			}
			elseif(user::checkUserExist($_POST['name'])) {
				// check this name is a member of this website?
				echo '<code>6</code>';
			}
			else {
				$login->LogRegistry($name,0,0);
				echo "<code>2</code><type>".$type_user[0].'</type><name>'.$name."</name>";
			}
		}
		
	}
	elseif($logintype == "own") {
		$pass = $_POST['pass'];
		$name   = $_POST['name'];
		if(empty($pass)) {
			$_SESSION['temp_name_user_login_bonus'] = $name;
			echo '<code>3</code>';	
		}
		else {
			$pass   = md5($pass);
			$name = $_SESSION['temp_name_user_login_bonus'];
			$login->checkLogin($name,$pass);
			if($login->getLogged() == TRUE) {
				$user_info = $login->getInfo();
				//print_r($user_info);
				echo "<code>0</code><type>".$type_user[$user_info['user_type']].'</type><name>'.$user_info['user_name']."</name>";
			}
			else {
				echo '<code>1</code>';
			}
		}
	}
    
   echo "</community>"; 
}



?>