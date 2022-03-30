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
if(!isset($plugin_params)) global $plugin_params; 
$config = explode ( ',', $plugin_params['params'] );
// Note: order of config array:
// 0 : Type comment : frontpage, blog (content) ,...
// 1 : Width of Textarea.
global $_URL;
$db =  new DBmanager();
if($_POST['comment_params'] != '') {
	$comment_params 	= $db->escapeString($_POST['comment_params']);
	$comment_params_width = $db->escapeString($_POST['comment_params_width']);
	$com_query 			= 'UPDATE '.PREFIX.'plugin SET `params`="'.$comment_params.','.$comment_params_width.'" WHERE  `id`="'.$plugin_params['id'].'" ';
	$db->execute($com_query);
}
	$com_query 			= 'SELECT params FROM '.PREFIX.'plugin  WHERE  `id`="'.$plugin_params['id'].'" ';
	$db->execute($com_query);
	$p_num = $db->count();
		if($p_num > 0) {
			$p_re   = $db->getRow();
			$config = explode ( ',', $p_re['params'] );
		}
	// Default Setting
	if (empty ( $config [0] ))	$config [0] = 'blog';
	if (empty ( $config [1] ))	$config [1] = 50;
	echo '<tr>
			<td>Type Component </td><td><select name="comment_params"><option '.($config[0]=='blog'?'selected=selected':'').' value="blog"> blog </option></select>
			</td>
		  </tr>';
	echo '<tr>
			<td>Width </td><td><input type=text name="comment_params_width" value="'. $config [1].'" size=5>columm</td>
		  </tr>';


?>