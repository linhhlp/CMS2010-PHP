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
 * This module is based on a mod of joomla but it was fixed to run in our framework.
 * 
 */
/**
 * Setting:
 * 	 global $mod_params 
 */
if(!isset($mod_params)) global $mod_params;
if(!isset($_URL)) global $_URL;
$url = $_URL->getNewUrl ();
$config = explode ( ',', $mod_params );
// Note: order of config array:
// 0 : Yahoo ID : who want to statistics
if (empty ( $config [0] )) $config [0] = 'kim_le_duc_89';

$yahooid =  $config [0];
$image_online = IMA . "/online15.gif";
$image_offline = IMA . "/offline15.gif";
$pageurl = "http://mail.opi.yahoo.com/online?u=" . $yahooid . "&m=a&t=1";
if (function_exists ( 'curl_init' )) {
	$ch = curl_init ();
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt ( $ch, CURLOPT_URL, $pageurl );
	$status = curl_exec ( $ch );
	if ($status == "01") {
		$online = true;
	} else {
		$online = false;
	}
	curl_close ( $ch );
} else {
	if (is_file ( $pageurl )) {
		$file = fopen ( $pageurl, "r" );
		$read = fread ( $file, 200 );
		$read = ereg_replace ( $yahooid, "", $read );
		$y = strstr ( $read, "00" );
		$z = strstr ( $read, "01" );
		if ($y) {
			$online = false;
		} elseif ($z) {
			$online = true;
		}
		fclose ( $file );
	} else
		$online = false;
}
echo '<div align="right"><a href="ymsgr:sendIM?' . $yahooid . '">CHAT YAHOO<br />';

if ($online) {
	echo '<img src="' . $image_online . '" border="0" alt="online status"/>';
} else {
	echo '<img src="' . $image_offline . '" border="0"  alt="offline status" />';
}

echo '</a></div>';

?>