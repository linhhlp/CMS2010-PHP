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

/**.
 * This feature helps us config comment module in ControlPanel.
 */
if(!isset($mod_params)) global $mod_params; 
$config = explode ( ',', $mod_params['params'] );
// Note: order of config array:
// 0 : Type comment : frontpage, blog (content) ,...
// 1 : Number of comment per page (main content).
// 2 : Number of letters extract
// 3 : Auto hide content.
global $_URL;
$db =  new DBmanager();
if($_POST['blog_params'] != '') {
	$blog_params 	= $db->escapeString($_POST['blog_params']);
	$blog_params_num = $db->escapeString($_POST['blog_params_num']);
	$blog_params2 	= $db->escapeString($_POST['blog_params2']);
	$blog_params3 	= $db->escapeString($_POST['blog_params3']);
	$com_query 			= 'UPDATE '.PREFIX.'module SET `params`="'.$blog_params.','.$blog_params_num.','.$blog_params2.','.$blog_params3.'" WHERE  `id`="'.$mod_params['id'].'" ';
	$db->execute($com_query);
}
	$com_query 			= 'SELECT params FROM '.PREFIX.'module  WHERE  `id`="'.$mod_params['id'].'" ';
	$db->execute($com_query);
	$p_num = $db->count();
		if($p_num > 0) {
			$p_re   = $db->getRow();
			$config = explode ( ',', $p_re['params'] );
		}
	// Default Setting
	if (empty ( $config [0] ))	$config [0] = '0';
	if (empty ( $config [1] ))	$config [1] = 5;
	if (empty ( $config [2] ))	$config [2] = 200;
	if (empty ( $config [3] ))	$config [3] = "off";
	echo '<tr>
			<td>Category: 0 for all, ID for each categories<br />For example: 1,2 </td><td><input type=text name="blog_params" value="'. $config [0].'">
			</td>
		  </tr>';
	echo '<tr>
			<td>Number comments </td><td><input type=text name="blog_params_num" value="'. $config [1].'" size=5></td>
		  </tr>';
	echo '<tr>
			<td>Number of letters extract</td><td><input type=text name="blog_params2" value="'. $config [2].'" size=5></td>
		  </tr>';
	echo '<tr>
			<td>Auto hide content</td><td><input type=text name="blog_params3" value="'. $config [3].'" size=5>on/off</td>
		  </tr>';


?>