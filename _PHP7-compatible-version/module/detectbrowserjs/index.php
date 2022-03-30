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

// this module check user browser if be supported or not
/**
 * Setting:
 * 	 global $mod_params 
 */
if(!isset($mod_params)) global $mod_params;
$config = explode ( ',', $mod_params );
// Note: order of config array:
// 0 : Url redirect
// 1 : Notice content
// 2 : type notify: alert popup or textbox
if($_SESSION['detectbrowserjs'] == '' ) {
echo '<script type="text/javascript">
</script>';
$_SESSION['detectbrowserjs'] = 'noticed'; 
}

?>
document.write('<p class="accent">You\'re using ' + BrowserDetect.browser + ' ' + BrowserDetect.version + ' on ' + BrowserDetect.OS + '!</p>');
