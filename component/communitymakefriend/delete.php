<?php
/**
 * @author HLP4ever .
 * @version		2010
 * @package		HLP4ever community.
 * @copyright	Copyright (C) 2010 by HLP.
 * @license		Open Source and free for non-conmecial
 */
defined('START') or die;
/**
 * Do not change code below, only set up your config if you're sure you've known.
 */
// Check if It can be delete or not
$can_delete = TRUE;
$get_id = $community->getConfig("","",$url[2]);
if($get_id[0]["value1"] == "invitation" && $get_id[0]["value2"] == "out") {
	switch($get_id[$i]['settings']) {
		case "unread";  $can_delete = FALSE;	break;
		case "process"; $can_delete = FALSE;	break;
		case "accepted";$can_delete = TRUE;		break;
		case "denied"; 	$can_delete = TRUE;		break;
	}
}
if($can_delete == TRUE) {
	$del 	= $community->deleteConfig($url[2]);
	if($del == FALSE) {
		echo "Không thể xóa lời mời ID: ".$url[2];	
	}
	else {
		echo "Xóa thành công";
	}
}
else echo "Không thể xóa lời mời ID: ".$url[2];