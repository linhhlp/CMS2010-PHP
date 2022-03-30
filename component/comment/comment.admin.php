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
// 0 : Type comment : frontpage, blog (content) ,...
// 1 : Width of Textarea.
// 2 : (Optional)   :id: Identification of blog content.
// 3 : Number of comment per page (main content)
global $_URL;
$db =  new DBmanager();
if($_POST['comment_params'] != '') {
	$comment_params 	= $db->escapeString($_POST['comment_params']);
	$comment_params_num = $db->escapeString($_POST['comment_params_num']);
	$comment_params_width = $db->escapeString($_POST['comment_params_width']);
	$com_query 			= 'UPDATE '.PREFIX.'component SET `params`="'.$comment_params.','.$comment_params_width.','.$comment_params_num.'" WHERE  `id`="'.$com_params['id'].'" ';
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
	if (empty ( $config [0] ))	$config [0] = 'frontpage';
	if (empty ( $config [1] ))	$config [1] = 200;
	if (empty ( $config [2] ))	$config [2] = 20;
	echo '<tr>
			<td>Type Component </td><td><select name="comment_params"><option '.($config[0]=='blog'?'selected=selected':'').' value="Front page">Front page </option></select>
			</td>
		  </tr>';
	echo '<tr>
			<td>Width </td><td><input type=text name="comment_params_width" value="'. $config [1].'" size=5>columm</td>
		  </tr>';
	echo '<tr>
			<td>Number comments per page </td><td><input type=text name="comment_params_num" value="'. $config [2].'"  size=5></td>
		  </tr>';


?>