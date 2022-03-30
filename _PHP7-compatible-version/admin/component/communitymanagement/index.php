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
 * This manages all feature
 *    such as: creating and management acconuts
 */
 
if(!isset($_URL)) global $_URL;
$url = $_URL->getNewUrl ();
if(!isset($login) )global $login;
$user_info = $login->getInfo();

$community = new community();
// It returns menu in Community management 
//    depend type of user (global admin user or private user)
// super admin
if($user_info['user_type'] == 4) {
	//$menutype = 'adminmenu';
	$menutype = '';	// all menu for admin
}
else {
	//personal user
	$menutype = 'usermenu';
}
$menu = $community->getConfig('menu',$menutype);
if( !empty($menu )) {
	foreach($menu as $key=>$value) {
	echo urlmanager::makeLink($value['value1'],$value['value2'],$value['value3'],$value['value4'],$value['value4']);
	}
}


?>