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
 * This is search module which help to find information.
 */

?>


<div class=search>
		<form action=<?php echo urlmanager::makeLink('search','','')?> method=POST>
<input type=text name=search size=15 value="<?php echo $_POST['search'];?>" >
		<?php echo urlmanager::makeLink('search','advanced','','tim kiem nang cao','Chuyên sâu','class="search_adv_link"')?>
		</form>
</div> 	

