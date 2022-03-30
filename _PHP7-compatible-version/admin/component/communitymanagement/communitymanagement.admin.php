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
	$link = $db->escapeString($_POST['path']);
	$check_exist_menu_query = "SELECT * FROM `".PREFIX."menu` WHERE  `link`='".$link."' AND `type`='adminmenu'";
	$db->execute($check_exist_menu_query);
	if($db->count() == 0 ) {
		echo "Tạo menu";
		$db->execute("INSERT INTO `".PREFIX."menu` (`title`,`alias`,`link`,`order`,`type`) VALUES ('Community','Community','".$link."','10','adminmenu')");
	}
community::createDB();	
}
