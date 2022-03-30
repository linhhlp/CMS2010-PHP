<?php
/**
 * @author HLP4ever .
 * @version		2010
 * @package		HLP4ever blog.
 * @copyright	Copyright (C) 2010 by HLP.
 * @license		Open Source and free for non-conmecial
 * This is Admin site which is coded in PHP by HLP4ever.
 * Thank you for using it.
 * If it gets a trouble or error, please mail me by email : linh.hlp@gmail.com
 * For documentation, try this link: hlp007.com/code or hlp7.com/code
 * 
 * This is index file of System Management.
 * 
 */
define('START', 'started');
/**
 * This is control panel for our web.
 * The super admin will control everything.
 */
?>
<?php
ini_set('display_errors',0);
if (! isset ( $_SESSION ['ss'] )) {
	session_start ();
	$_SESSION ['ss'] = 1;
}
$_SESSION['makeup_admin_site']= 'admin'; 	// Make up that is admin site
/**
 * Those are configs for admin page script.
 * Please note that: Do not change If You are not sure!
 */
// change the working directory (from admin dir to root dir)
chdir(dirname( getcwd()));
// This return the link of this file (for example /admin/index.php)
$old_cwd = $_SERVER['PHP_SELF'];

//echo getcwd();
//echo $old_cwd;
// find config
if (!is_file('config.php')) {
	// RUNNING SET UP TO INSTALL
	if(is_file('install.php')) {
		$admin_url = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		$length_admin_url = strlen($admin_url);
		if($admin_url[$length_admin_url -1] == "/") $admin_url = substr($admin_url,0,$length_admin_url - 2);
		$install_url  = substr($admin_url,0,strripos( $admin_url ,"/"));
		header('Location: '.$install_url.'/install.php');
	}
	else {
		echo 'Can not find install.php file.';
		exit;
	}

} else {
	if(is_file('install.php')|| is_dir('install')) {
			echo 'After installation please <font color=red>DELETE or REMOVE</font> INSTALL Folder and install.php file!';
			return;
		}
	// Start website:
	//load config
	include_once 'config.php';
	$_CONFIG = new Config();
		// Include necessary files
	if(is_file(LIB.'/function.php')) {
		include_once (LIB.'/function.php');
	    
	} else {
		echo ( 'Can not find function.php file.' );
		exit ();
	}
	// remove escape in POST and GET data.
	if(ini_get("magic_quotes_gpc") == 1) {
		removeEscape();
	}
	
	$admin_HLP  = $_SERVER ['SCRIPT_NAME'];
	global $THEME_PATH;
	$_URL 	= new urlmanager ();
	$_NEWURL= $_URL->getNewUrl();
	$PARAS 	= $_NEWURL['0'];
	$ACTION	= $_NEWURL['1'];
	$CONTROL= $_NEWURL['2'];
	//Loading...
	//Log-in
	
	global $login;
	$login = new login();
	$login->checkLogin();
	
	//Template
	$_TEMP 	= new template('admin');
	echo $_TEMP->loadTemp(); 
	
}
$_SESSION['makeup_admin_site'] = ''; 	// UnSET make up that is admin site
?>