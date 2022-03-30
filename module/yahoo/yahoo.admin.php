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
// 0 : Yahoo ID : who want to statistics

global $_URL;
$db =  new DBmanager();
if($_POST['yahoo_params_id'] != '') {
	$yahoo_params_id 	= $db->escapeString($_POST['yahoo_params_id']);
	$com_query 			= 'UPDATE '.PREFIX.'module SET `params`="'.$yahoo_params_id.'" WHERE  `id`="'.$mod_params['id'].'" ';
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
	if (empty ( $config [0] ))	$config [0] = 'kim_le_duc_89';
	echo '<tr>
			<td>User ID </td><td><input type=text name="yahoo_params_id" value="'. $config [0].'" size=20></td>
		  </tr>';




?>