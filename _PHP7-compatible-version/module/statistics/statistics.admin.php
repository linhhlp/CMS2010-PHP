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
 * This feature helps us config statistics module in ControlPanel.
 */
if(!isset($mod_params)) global $mod_params; 
$config = explode ( ',', $mod_params['params'] );
// Note: order of config array:
// 0 : User ID : who want to statistics
// 1 : Statistics by User		: ON or OFF
// 2 : Statistics by Comment	: ON or OFF
// 3 : Statistics by Contents each Category: ON or OFF
global $_URL;
$db =  new DBmanager();
if($_POST['statistics_params_user'] != '') {
	$statistics_params_user_id 	= $db->escapeString($_POST['statistics_params_user_id']);
	$statistics_params_user 	= $db->escapeString($_POST['statistics_params_user']);
	$statistics_params_comment 	= $db->escapeString($_POST['statistics_params_comment']);
	$statistics_params_content 	= $db->escapeString($_POST['statistics_params_content']);
	$com_query 			= 'UPDATE '.PREFIX.'module SET `params`="'.$statistics_params_user_id.','.$statistics_params_user.','.$statistics_params_comment.','.$statistics_params_content.'" WHERE  `id`="'.$mod_params['id'].'" ';
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
	if (empty ( $config [0] ))	$config [0] = '1';
	if (empty ( $config [1] ))	$config [1] = 'ON';
	if (empty ( $config [2] ))	$config [2] = 'ON';
	if (empty ( $config [3] ))	$config [3] = 'ON';
	echo '<tr>
			<td>User ID </td><td><input type=text name="statistics_params_user_id" value="'. $config [0].'" size=5></td>
		  </tr>';
	echo '<tr>
			<td>User Statitis Enable </td><td><select name="statistics_params_user"><option '.($config[1]=='ON'?'selected=selected':'').' value="ON"> Enable </option><option '.($config[1]=='OFF'?'selected=selected':'').' value="OFF"> Disale </option></select>
			</td>
		  </tr>';
	echo '<tr>
			<td>Comment Statitis Enable </td><td><select name="statistics_params_comment"><option '.($config[2]=='ON'?'selected=selected':'').' value="ON"> Enable </option><option '.($config[2]=='OFF'?'selected=selected':'').' value="OFF"> Disale </option></select>
			</td>
		  </tr>';
	echo '<tr>
			<td>Content Statitis Enable </td><td><select name="statistics_params_content"><option '.($config[3]=='ON'?'selected=selected':'').' value="ON"> Enable </option><option '.($config[3]=='OFF'?'selected=selected':'').' value="OFF"> Disale </option></select>
			</td>
		  </tr>';



?>