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
 * This module manage your module.
 * 
 */

global $admin_HLP;
global $THEM_PATH;
global $PARAS;
global $ACTION;
global $CONTROL;
global $_CONFIG;
if(!isset($com_path)) global $com_path;
$permission_list = $_CONFIG->getPermission();
if(!isset($login) )global $login;
$user_info = $login->getInfo();

// process permission
if(  empty($community_permission)) global $community_permission;
 // allow check permission or not
global $per_check;
if( !empty($community_permission) ) {
	$module_per = $community_permission["component"]['admin']["managemodule"];
	$per_check = TRUE;
}
else {
	// default
	$per_check = FALSE; // do not check permission (full access)
	$module_per = "";
}
	$module_per = explode(";",$module_per);
	// order module_per : install-update-delete
	// Install permission
	$per_install		= explode("-",$module_per [0]);
	$per_install_global = explode(",",$per_install[0]);
	$per_install_user	= explode(",",$per_install[1]);
	// Update permission
	$per_update			= explode("-",$module_per[1]);
	$per_update_global  = explode(",",$per_update[0]);
	$per_update_user	= explode(",",$per_update[1]);
	// Delete permission
	$per_delete 		= explode("-",$module_per[2]);
	$per_delete_global  = explode(",",$per_delete[0]);
	$per_delete_user	= explode(",",$per_delete[1]);
?>
<br /><br />
<?php 

$mana 	 = new managemodule();
if($ACTION == 'module' || $ACTION == 'install_module') {
	include($com_path.'/modulemanager.php');
}
elseif($ACTION == 'install_component'||$ACTION == 'component') {
	include($com_path.'/componentmanager.php');
}
elseif($ACTION == 'position' || $ACTION == 'create_position') {
	include($com_path.'/positionmanager.php');
}
elseif($ACTION == 'plugin' || $ACTION == 'create_plugin') {
	include($com_path.'/pluginmanager.php');
}
else {
	include($com_path.'/display.php');
}

?>