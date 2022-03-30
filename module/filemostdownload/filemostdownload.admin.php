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
 * This feature helps us config type category will show in.
 */
if(!isset($mod_params)) global $mod_params; 
$config = explode ( ',', $mod_params['params'] );
// Note: order of config array:
// 0 : category ID
// 1 : Number of files per page (main content)
// 2 : Title : Most download files news, Most view file module
// 3 : type order: most download, most view, extension,...

global $_URL;

$db =  new DBmanager();
if($_POST['dbfile_params'] != '') {
	$dbfile_params 	= $db->escapeString($_POST['dbfile_params']);
	$dbfile_params_style = $db->escapeString($_POST['dbfile_params_style']);
	$dbfile_params_style2 = $db->escapeString($_POST['dbfile_params_style2']);
	$dbfile_params_style3 = $db->escapeString($_POST['dbfile_params_style3']);
	$dbfile_query 			= 'UPDATE '.PREFIX.'module SET `params`="'.$dbfile_params.','.$dbfile_params_style.','.$dbfile_params_style2.','.$dbfile_params_style3.'" WHERE  `id`="'.$mod_params['id'].'" ';
	$db->execute($dbfile_query);
}
	$com_query 			= 'SELECT params FROM '.PREFIX.'module  WHERE  `id`="'.$mod_params['id'].'" ';
	$db->execute($com_query);
	$p_num = $db->count();
		if($p_num > 0) {
			$p_re   = $db->getRow();
			$config = explode ( ',', $p_re['params'] );
		}
	// Default Setting
	if (empty ( $config [0] ))	$config [0] = '0';
	if (empty ( $config [1] ))	$config [1] = '3';
	if (empty ( $config [2] ))	$config [2] = 'Download nhiều nhất:';
	if (empty ( $config [3] ))	$config [3] = 'download';
	echo '<tr>
			<td>Title </td><td><input type=text name="dbfile_params_style2" value="'.$config[2].'">
			</td>
		  </tr>';
	echo '<tr>
			<td>Numbers display </td><td><input type=text name="dbfile_params_style" value="'.$config[1].'">
			</td>
		  </tr>';
	echo '<tr>
			<td>Category ID (0 : all) </td><td><input type=text name="dbfile_params" value="'.$config[0].'">
			</td>
		  </tr>';
	echo '<tr>
			<td>Type order(most download, most view,...) </td><td><select name="dbfile_params_style3" >
				<option value="download" '.($config [3]=="download"?'selected':'').'>Most download</option>
				<option value="view" '.($config [3]=="view"?'selected':'').'>Most view</option>
				<option value="created" '.($config [3]=="created"?'selected':'').'>New upload file</option>
				</select></td>
		  </tr>';

