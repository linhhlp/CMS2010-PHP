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
 * Setting of this module.
 * Notice that, the template has already load 2 variables:
 * $mod_path   : the path of module
 * $mod_params : the configs for module
 */
?>
<u><span style="text-decoration: overline">
	
<?php
	$info = new visitor();
	echo "Lượt truy cập ".$info->Visit();	
	echo '<br />';
	// Số IP
	echo "IP của bạn ".$info->getIp();
?>
	<br /></span></u>
