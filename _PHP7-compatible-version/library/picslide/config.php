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
 * This feature helps us config Picture slide.
 */

$config = array();
// Note: order of config array:
// 0 : width   audio player.
// 1 : height  audio player.


// Default Setting
if (empty ( $config [0] ))	$config [0] = '330';
if (empty ( $config [1] ))	$config [1] = '330';

?>