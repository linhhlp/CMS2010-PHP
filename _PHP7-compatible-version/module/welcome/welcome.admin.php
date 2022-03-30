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
// 0 : Slogan
// 1 : Image link
global $_URL;
$db =  new DBmanager();
if($_POST['vistor_params_slogan'] != '') {
	$vistor_params_slogan 	= $db->escapeString($_POST['vistor_params_slogan']);
	$vistor_params_slogan2 	= $db->escapeString($_POST['vistor_params_slogan2']);
	$com_query 			= 'UPDATE '.PREFIX.'module SET `params`="'.$vistor_params_slogan.','.$vistor_params_slogan2.'" WHERE  `id`="'.$mod_params['id'].'" ';
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
	if (empty ( $config [0] ))	$config [0] = 'Web cá nhân của HLP4ever';
	echo '<tr>
			<td> Slogan </td><td><input type=text name="vistor_params_slogan" value="'. $config [0].'" size=20></td>
		  </tr>';
	echo '<tr>
			<td> Image </td><td><input type=text name="vistor_params_slogan2" value="'. $config [1].'" size=20></td>
		  </tr>';



?>