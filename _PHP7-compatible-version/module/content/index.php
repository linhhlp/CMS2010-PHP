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
 * This is Content module.
 * It manages our content like: article, music, video,...
 * This it controller file.
 * It calls the module corresponding to module.
 * It mean if you are in http://localhost/fw/hlp/blog/0/0/blog-page.hlp2010.html
 * It will call blog module.
 * So if you want to change CSS style, see in view.php file
 */

global $_URL;
$temp = $_URL->getNewUrl();
$PARA = $temp[0];
$content = new content($PARA);
echo $content->getContent();

?>
