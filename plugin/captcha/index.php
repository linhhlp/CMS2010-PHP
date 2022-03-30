<?php

/**
* HLP4ever 2010
* Convert to plugin of HLP4ever framework.
*/

// No direct access
defined('START') or die;

/**
 * Setting:
 * 	 global $mod_params 
 */
if(!isset($plugin_params)) global $plugin_params;
if(!isset($plugin_path)) global $plugin_path;
//print_r($com_params);
if(!isset($_URL)) global $_URL;
$url = $_URL->getNewUrl ();
$config = explode ( ',', $plugin_params['params'] );
// Note: order of config array:
// 0 : width
// 1 : height
// 2 : character numbers
// 3 : who can pass without validating :	all - member - register
if(empty($config [0])) $config [0] = 120;
if(empty($config [1])) $config [1] = 40;
if(empty($config [2])) $config [2] = 4;

// check post 
//$_SESSION['captcha_code_valid'] = FALSE;
if(!isset($login) )global $login;
$user_info = $login->getInfo();
if( empty($config [3] ) || ($config [3] == "member" && $user_info["user_type"]  != 0) ||($config [3] == "register" && $user_info["user_name"]  != '') || $config [3] == "all" ) {
return;
}
// Print captcha image
echo 'Security code<br />';
echo ' <img src="'.$plugin_path.'/CaptchaSecurityImages.php?width='.$config [0].'&height='.$config [1].'&characters='.$config [2].'" width="'.$config [0].'" height="'.$config [1].'"/>';
echo '<input id="captcha_code" name="captcha_code" type="text" />';
echo '<br />';

?>