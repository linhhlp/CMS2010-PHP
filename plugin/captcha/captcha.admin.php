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
 * This feature helps us config captcha
 */
if(!isset($plugin_params)) global $plugin_params; 
$config = explode ( ',', $plugin_params['params'] );
// Note: order of config array:
// 0 : width
// 1 : height
// 2 : character numbers
// 3 : who can pass without validating :	all - member - register
// 4 : name of startup file
global $_URL;
$db =  new DBmanager();
if($_POST['captcha_params1'] != '') {

	$captcha_params1 	= $db->escapeString($_POST['captcha_params1']);
	$captcha_params2 	= $db->escapeString($_POST['captcha_params2']);
	$captcha_params3 	= $db->escapeString($_POST['captcha_params3']);
	$captcha_params4 	= $db->escapeString($_POST['captcha_params4']);
	$com_query 			= 'UPDATE '.PREFIX.'plugin SET `params`="'.$captcha_params1.','.$captcha_params2.','.$captcha_params3.','.$captcha_params4.','.$captcha_params5.'" WHERE  `id`="'.$plugin_params['id'].'" ';
	$db->execute($com_query);
	$file_startup		 = STUP.'/'.$_POST['captcha_params5'];
	$handle = @fopen($file_startup, "r");
	$setting_startup = '';
	if ($handle) {
		while (!feof($handle)) {
			$aline = fgets($handle);
			if(is_int ( stripos($aline,'$setting = ')) ) $setting_startup .=  '$setting = "'.$captcha_params4.'";';
			else $setting_startup .=  $aline;
		}
		fclose($handle);
	}

	file_put_contents($file_startup, $setting_startup);
}
	$com_query 			= 'SELECT params FROM '.PREFIX.'plugin  WHERE  `id`="'.$plugin_params['id'].'" ';
	$db->execute($com_query);
	$p_num = $db->count();
		if($p_num > 0) {
			$p_re   = $db->getRow();
			$config = explode ( ',', $p_re['params'] );
		}
	// Default Setting
	if(empty($config [0])) $config [0] = 120;
	if(empty($config [1])) $config [1] = 40;
	if(empty($config [2])) $config [2] = 4;
	if(empty($config [4])) $config [4] = 'startup.CaptchaSecurityImages.php';

	echo '<tr><td>Image width</td><td><input type=text name="captcha_params1" value="'. $config [0].'"  size=5>pixel </td>  </tr>';
	echo '<tr><td>Image height </td><td><input type=text name="captcha_params2" value="'. $config [1].'"  size=5>pixel </td> </tr>';
	echo '<tr><td>Number of character </td><td><input type=text name="captcha_params3" value="'. $config [2].'"  size=5></td> </tr>';
	echo '<tr>
			<td>who can pass without validating </td><td><select name="captcha_params4">
				<option '.($config[3]=='all'?'selected=selected':'').' value="all">All user </option>
				<option '.($config[3]=='member'?'selected=selected':'').' value="member">Member user </option>
				<option '.($config[3]=='register'?'selected=selected':'').' value="register">Register </option>
				</select>
			</td>
		  </tr>';
	echo '<tr><td>Name of startup file (advanced)</td><td><input type=text name="captcha_params5" value="'. $config [4].'"  size=30></td> </tr>';
?>