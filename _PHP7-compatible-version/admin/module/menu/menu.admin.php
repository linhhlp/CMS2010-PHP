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
if(!isset($mod_params)) global $mod_params; 
$config = explode ( ',', $mod_params['params'] );
// Note: order of config array:
// 0 : (Name) Type menu : top menu, left menu ,...
// 1 : Style of menu.

global $_URL;

$db =  new DBmanager();
if($_POST['menu_params'] != '') {
	$menu_params 	= $db->escapeString($_POST['menu_params']);
	$menu_params_style = $db->escapeString($_POST['menu_params_style']);
	$menu_query 			= 'UPDATE '.PREFIX.'module SET `params`="'.$menu_params.','.$menu_params_style.'" WHERE  `id`="'.$mod_params['id'].'" ';
	$db->execute($menu_query);
}
	$com_query 			= 'SELECT params FROM '.PREFIX.'module  WHERE  `id`="'.$mod_params['id'].'" ';
	$db->execute($com_query);
	$p_num = $db->count();
		if($p_num > 0) {
			$p_re   = $db->getRow();
			$config = explode ( ',', $p_re['params'] );
		}
	// Default Setting
	if (empty ( $config [0] ))	$config [0] = 'leftmenu';
	if (empty ( $config [1] ))	$config [1] = 'default';
	echo '<tr>
			<td>Type Module (Name) </td><td><input type=text name="menu_params" value="'.$config[0].'">
			</td>
		  </tr>';
	echo '<tr>
			<td>Style Menu (Theme) </td><td><input type=text name="menu_params_style" value="'. $config [1].'" size=5>columm</td>
		  </tr>';


?>