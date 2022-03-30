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
 * This module manages newest info such as:
 *  	contents: blog, music, video, comment...
 */

/**
 * Config:
 */
global $mod_path,$mod_params;
if(is_file($mod_path.'/info.class.php')) {
	include_once ($mod_path.'/newest.class.php');
}
$config = explode(',',$mod_params);

$newest = new newset();
if($newest->getNewest() != FALSE) {
	echo	"<div id=THONG_TIN_MOI_NHAT_HLP ><font size=4 style='background-color:white'><font color=red>";
	foreach ($newest->getNewest() as $key=>$new) {
		if(empty($new)) $new_content = 'Chưa có bài viết mới';
	echo	"Bài viết mới : </font><a href=?hlp=blog style='color:green'>$new_content</a>";
			echo	"<br>";	
	}
	echo	"</div>";
}

?>