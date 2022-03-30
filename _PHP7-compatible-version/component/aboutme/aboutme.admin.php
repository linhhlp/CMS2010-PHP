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
if($_POST['aboutme_params'] != '') {
	$aboutme_params 	= $db->escapeString($_POST['aboutme_params']);
	$com_query 			= 'UPDATE '.PREFIX.'component SET `params`="'.$aboutme_params.'" WHERE  `id`="'.$com_params['id'].'" ';
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
	$user = new user();
	$user_info = $user->getUserName('','id');
	echo '<tr>
			<td>User </td><td><select name="aboutme_params">';
	foreach($user_info as $key=>$value) {		
			echo '<option '.($config[0]==$key?'selected=selected':'').' value="'.$key.'">'.$value.'('.$key.') </option>';
	}
	echo '	</select></td>
		  </tr>';

?>