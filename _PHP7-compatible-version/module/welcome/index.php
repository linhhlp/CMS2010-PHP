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
global $mod_path,$mod_params;
$config = explode(',',$mod_params);
// Note: order of config array:
// 0 : Slogan
// 1 : Image link
if (empty ( $config [0] ))	$config [0] = 'Web cá nhân của HLP4ever';

?>

	<div class="slogan" >
	<?php
		echo $config [0];
		if($config [1] !='') echo "<br /><img src='".$config [1]."' width='500px' />";
		
	?>
	</div >
