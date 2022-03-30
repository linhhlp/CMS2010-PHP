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
// 0 : Notice content
// 1 : Chrome support
// 2 : OmniWeb support
// 3 : Safari support
// 4 : Opera support
// 5 : iCab support
// 6 : Konqueror support
// 7 : Firefox support
// 8 : Camino support
// 9 : Netscape support
//10 : Explorer support
global $_URL;
$db =  new DBmanager();
if($_POST['jsnotice_params'] != '') {
	$jsnotice_params 	= $db->escapeString($_POST['jsnotice_params']);
	$jsnotice_params2 	= $db->escapeString($_POST['jsnotice_params2']);

	$com_query 			= 'UPDATE '.PREFIX.'module SET `params`="'.$jsnotice_params.','.$jsnotice_params2.'," WHERE  `id`="'.$mod_params['id'].'" ';
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
	if (empty ( $config [0] ))	$config [0] = '';
	if (empty ( $config [1] ))	$config [1] = 'redirect to new url';

	echo '<tr><td>Url redirect </td><td><input type=text name="jsnotice_params" value="'. $config [0].'"  size=30> </td>  </tr>';
	echo '<tr><td>Notice content </td><td><input type=text name="jsnotice_params2" value="'. $config [1].'"  size=30> </td> </tr>';


?>