<?php/** * @author HLP4ever . * @version		2010 * @package		HLP4ever blog. * @copyright	Copyright (C) 2010 by HLP. * @license		Open Source and free for non-conmecial */// No direct accessdefined('START') or die;/** *  This feature process invitations or send a invitation. *   */// setting separation between string value//define(MKFR_V, ":-:"); // between main value//define(MKFR_F, ":_-_:"); // between type friend// Send a invitationif(!isset($_URL)) global $_URL;$url = $_URL->getNewUrl(); $community = new community();if(!isset($com_path)) global $com_path;if($url[1] == "delete") {	include($com_path.'/delete.php');	}elseif($url[1] == "view") {	include($com_path.'/view.php');	}elseif($url[1] == "send") {	include($com_path.'/send.php');	}elseif($url[1] == "listfriend") {	include($com_path.'/listfriend.php');}elseif($url[1] == "seeall") {	include($com_path.'/seeall.php');}else {	echo "<br />".urlmanager::makeLink($url[0], "send", "","send a invitation","<h3>Gửi một lời mời kết bạn<h3>","style='color:red;'")."<br />";	echo urlmanager::makeLink($url[0], "seeall", "","see all invitations","<h3>Xem tất cả các thư mời<h3>","style='color:red;'")."<br />";	echo urlmanager::makeLink($url[0], "listfriend", "","list all friend","<h3>Xem danh sách bạn bè<h3>","style='color:red;'")."<br />";	} ?> <style> .makefriend h2 { color:yellow; } .mkfr_from { color:red; } .mkfr_emp { color:yellow; font-weight:bold; } </style>