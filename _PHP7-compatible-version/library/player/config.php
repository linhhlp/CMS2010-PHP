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
 * This feature helps us config Player.
 */
 
$config = array();
// Note: order of config array:
// 0 : width   audio player.
// 1 : height  audio player.
// 2 : setting audio player.
// 3 : width    video player.
// 4 : height   video player.
// 5 : setting  video player.
// 6 : width   playlist player.
// 7 : height  playlist player.
// 8 : size   playlist player.
// 9 : setting playlist player.

// Default Setting
if (empty ( $config [0] ))	$config [0] = '300';
if (empty ( $config [1] ))	$config [1] = '20';
if (empty ( $config [2] ))	$config [2] = '&amp;displayclick=stop&amp;backcolor=000000&amp;frontcolor=990000&amp;lightcolor=990000';
if (empty ( $config [3] ))	$config [3] = '330';
if (empty ( $config [4] ))	$config [4] = '300';
if (empty ( $config [5] ))	$config [5] = '&amp;displayclick=stop&amp;backcolor=000000&amp;frontcolor=990000&amp;lightcolor=990000';
if (empty ( $config [6] ))	$config [6] = '330';
if (empty ( $config [7] ))	$config [7] = '450';
if (empty ( $config [8] ))	$config [8] = '180';
if (empty ( $config [9] ))	$config [9] = '&amp;playlist=bottom&amp;backcolor=111111&amp;frontcolor=eeeeee&amp;plugins=gapro';

?>