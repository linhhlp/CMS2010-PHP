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
if(!isset($com_params)) global $com_params; 
$config = explode ( ',', $com_params['params'] );
// Note: order of config array:
// 0 : Number of letter extracted
// 1 : Number of comment per page (main content)
global $_URL;
$db =  new DBmanager();
if($_POST['userbonus_params_num'] != '') {
	$userbonus_params_num = $db->escapeString($_POST['userbonus_params_num']);
	$userbonus_params_width = $db->escapeString($_POST['userbonus_params_width']);
	$com_query 			= 'UPDATE '.PREFIX.'component SET `params`="'.$userbonus_params_width.','.$userbonus_params_num.'" WHERE  `id`="'.$com_params['id'].'" ';
	$db->execute($com_query);
}
	$com_query 			= 'SELECT params FROM '.PREFIX.'component  WHERE  `id`="'.$com_params['id'].'" ';
	$db->execute($com_query);
	$p_num = $db->count();
		if($p_num > 0) {
			$p_re   = $db->getRow();
			$config = explode ( ',', $p_re['params'] );
		}
	// Default Setting
	if (empty ( $config [0] ))	$config [0] = 200;
	if (empty ( $config [1] ))	$config [1] = 20;
	echo '<tr>
			<td>Number of letter extracted </td><td><input type=text name="userbonus_params_width" value="'. $config [0].'" size=5></td>
		  </tr>';
	echo '<tr>
			<td>Number contents per page </td><td><input type=text name="userbonus_params_num" value="'. $config [1].'"  size=5></td>
		  </tr>';


?>