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
 * This feature helps us config comment module in ControlPanel.
 */
if($_POST['path'] != '') {
	$db =  new DBmanager();
	// create menu in community manager
	$link = $db->escapeString($_POST['path']);
	$check_exist_menu_query = "SELECT * FROM `".PREFIX."community` WHERE  `name`='menu' AND `type`='usermenu' AND `value1`='".$link."'";
	$db->execute($check_exist_menu_query);
	if($db->count() == 0 ) {
		echo "Tạo menu";
		$db->execute("INSERT INTO `".PREFIX."community` (`name`,`type`,`value1`,`value4`) VALUES ('menu','usermenu','".$link."','Friend list and make friend')");
	}
	
	// register for communityupdate directory
	$check_register_query = "SELECT * FROM `".PREFIX."community` WHERE  `type`='community' and `name`='makefriend'";
	$db->execute($check_register_query);
	if($db->count() == 0 ) {
		echo " register";
		$db->execute("INSERT INTO `".PREFIX."community` (`name`,`type`) VALUES ('makefriend','community')");
	}
	
}
