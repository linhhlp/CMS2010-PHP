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
// commnunity/message/xxx
// $url[2] : 

// load javascript file (js.js from communitylogin module
if(is_file(MOD.'/communitylogin/js.js') ) {


}
else {
	echo 'Can not load javascript file. Please check path';
	return;
}
?>
<a href='<?php echo $_URL->getOldUrl (); ?>#renew' name='renew' onClick='manage_function(true)'><img src="<?php echo _URL.IMA; ?>/reload.jpg" title='Cập nhập lại'>Cập nhập lại</a><br /><br />
<script type="text/javascript">	 
   setTimeout("manage_function(false)",100);
</script>
<div id="mess_display_userbonus"></div>
<br />
<div id="friend_display_news_userbonus"></div>