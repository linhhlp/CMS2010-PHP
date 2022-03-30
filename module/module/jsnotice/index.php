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
 * Setting:
 * 	 global $mod_params 
 */
if(!isset($mod_params)) global $mod_params;
$config = explode ( ',', $mod_params );
// Note: order of config array:
// 0 : Url redirect
// 1 : Notice content
if($_SESSION['jsnotice'] == '' ) {
echo '<script type="text/javascript">
	if(confirm("'.$config[0].'")) {
		window.parent.location= "'.$config[1].'";
	}
</script>';
$_SESSION['jsnotice'] = 'noticed'; 
}

?>