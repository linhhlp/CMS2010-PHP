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
 * This feature helps us config content module in ControlPanel.
 */
if(!isset($mod_params)) global $mod_params; 
$config = explode ( ',', $mod_params['params'] );
// Note: order of config array:
// 0 : Default module will load in front-page
// 1 : undefined.
global $_URL;
$db =  new DBmanager();
if($_POST['content_params'] != '') {
	$content_params 	= $db->escapeString($_POST['content_params']);
	$content_params_query 			= 'UPDATE '.PREFIX.'module SET `params`="'.$content_params.'" WHERE  `id`="'.$mod_params['id'].'" ';
	$db->execute($content_params_query);
	
}
$com_query 			= 'SELECT params FROM '.PREFIX.'module  WHERE  `id`="'.$mod_params['id'].'" ';
$db->execute($com_query);
$p_num = $db->count();
	if($p_num > 0) {
		$p_re   = $db->getRow();
		$config = explode ( ',', $p_re['params'] );
	}
// Default Setting
if (empty ( $config [0] ))	$config [0] = 'frontpage';
echo '<tr>
		<td>Default module will load in Front-page<br />Note: Input name of module (folder name) </td><td><input type=text name="content_params" value="'. $config [0].'" size=20></td>
	  </tr>';


?>