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
// 1 : Number of comment per page (main content)
// 2 : Number of character extracted
// 3 : Auto hide or not
global $_URL;
$db =  new DBmanager();
if($_POST['comment_params'] != '') {
	$comment_params 	= $db->escapeString($_POST['comment_params']);
	$comment_params_num = $db->escapeString($_POST['comment_params_num']);
	$comment_params_extr = $db->escapeString($_POST['comment_params_extr']);
	$comment_params2 	= $db->escapeString($_POST['comment_params2']);
	$com_query 			= 'UPDATE '.PREFIX.'module SET `params`="'.$comment_params.','.$comment_params_num.','.$comment_params_extr.','.$comment_params2.'" WHERE  `id`="'.$mod_params['id'].'" ';
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
	if (empty ( $config [0] ))	$config [0] = 'blog';
	if (empty ( $config [1] ))	$config [1] = 5;
	if (empty ( $config [2] ))	$config [2] = 70;
	if (empty ( $config [3] ))	$config [3] = "off";
	echo '<tr>
			<td>Type Module </td><td><select name="comment_params"><option '.($config[0]=='blog'?'selected=selected':'').' value="blog">Blog </option></select>
			</td>
		  </tr>';
	echo '<tr>
			<td>Number comments </td><td><input type=text name="comment_params_num" value="'. $config [1].'" size=5></td>
		  </tr>';
	echo '<tr>
			<td>Number lettes extract</td><td><input type=text name="comment_params_extr" value="'. $config [2].'" size=5></td>
		  </tr>';
	echo '<tr>
			<td>Auto hide comment</td><td><input type=text name="comment_params2" value="'. $config [3].'" size=5>on/off</td>
		  </tr>';

?>