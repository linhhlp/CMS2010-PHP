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
// 0 : Type comment : blog(frontpage), acticle (content) ,...
// 1 : Width of Textarea.
// 2 : Row of Textarea.
// 3 : Text editor support or not.
// 4 : Number of comment per page (main content)
// 5 : Number of character extract
// 6 : (Optional)   :id: Identification of blog content. ( Belong)
// 7 : Disable captcha.
// 8 : Auto hide comment.
global $_URL;
$db =  new DBmanager();
if($_POST['comment_params'] != '') {
	$comment_params 	= $db->escapeString($_POST['comment_params']);
	$comment_params_num = $db->escapeString($_POST['comment_params_num']);
	$comment_params_width = $db->escapeString($_POST['comment_params_width']);
	$comment_params2 	= $db->escapeString($_POST['comment_params2']);
	$comment_params3 	= $db->escapeString($_POST['comment_params3']);
	$comment_params4 	= $db->escapeString($_POST['comment_params4']);
	$comment_params5 	= $db->escapeString($_POST['comment_params5']);
	$comment_params6 	= $db->escapeString($_POST['comment_params6']);
	$com_query 			= 'UPDATE '.PREFIX.'module SET `params`="'.$comment_params.','.$comment_params_width.','.$comment_params2.','.$comment_params3.','.$comment_params_num.','.$comment_params4.',,'.$comment_params5.','.$comment_params6.'," WHERE  `id`="'.$mod_params['id'].'" ';
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
	if (empty ( $config [0] ))	$config [0] = 'frontpage';
	if (empty ( $config [1] ))	$config [1] = 200;
	if (empty ( $config [2] ))	$config [2] = 5;
	if (empty ( $config [3] ))	$config [3] = "on";
	if (empty ( $config [4] ))	$config [4] = 10;
	if (empty ( $config [5] ))	$config [5] = 200;
	if (empty ( $config [7] ))	$config [7] = "off";
	if (empty ( $config [8] ))	$config [8] = "off";
	echo '<tr>
			<td>Type Module </td><td><select name="comment_params"><option '.($config[0]=='blog'?'selected=selected':'').' value="Front page">Front page </option></select>
			</td>
		  </tr>';
	echo '<tr>
			<td>Width of text area </td><td><input type=text name="comment_params_width" value="'. $config [1].'" size=5>columm</td>
		  </tr>';
	echo '<tr><td>Row of text area </td><td><input type=text name="comment_params2" value="'. $config [2].'"  size=5>rows</td>  </tr>';
	echo '<tr><td>Text editor support: </td><td><input type=text name="comment_params3" value="'. $config [3].'"  size=5>on/off </td> </tr>';
	echo '<tr><td>Number comments per page </td><td><input type=text name="comment_params_num" value="'. $config [4].'"  size=5></td> </tr>';
	echo '<tr><td>Number characters extracted </td><td><input type=text name="comment_params4" value="'. $config [5].'"  size=5></td> </tr>';
	echo '<tr><td>Disable Capcha </td><td><input type=text name="comment_params5" value="'. $config [7].'"  size=5>on/off</td> </tr>';
	echo '<tr><td>Auto hide comment </td><td><input type=text name="comment_params6" value="'. $config [8].'"  size=5>on/off</td> </tr>';


?>