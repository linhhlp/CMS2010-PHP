<?php
/**
 * @author HLP4ever .
 * @version		2010
 * @package		HLP4ever community.
 * @copyright	Copyright (C) 2010 by HLP.
 * @license		Open Source and free for non-conmecial
 */
defined('START') or die;
if ( !isset($login)) global $login;
$user_info = $login->getInfo();

/**
 * Do not change code below, only set up your config if you're sure you've known.
 */
$get_id 	 = $community->getConfig("","",$url[2]);
// set unread -> process
if($get_id[0]['settings'] ==  "unread") $set_process = $community->setConfig($url[2], "","","","","","","process");
//split content of a invitation
// 0: friendtype     1: friendname          2: comment      3: url	  4: id of come-out invitation
// id: row id		settings: status read or unread.
$get_info	 	= explode(":-:",$get_id[0]['value3']);
$friend_type	= explode(":_-_:", $get_info[0]);
$get_info[0]	= "<span class='mkfr_emp'>" .$friend_type[1]."</span> và <span class='mkfr_emp'>" .$friend_type[0]."</span>";
echo "<div class='mkfr_from'><h3>Thư mời bạn đã nhận: </h3>";
echo "<table border=1 width=100%>";
echo "<tr><td width=20%>Từ địa chỉ </td> <td ><a href='".$get_info[3] ."' target='_blank'>".$get_info[3]."</a></td></tr>";
echo "<tr><td> Tên </td><td>".$get_info[1] ."</td></tr>";
echo "<tr><td>Mối quan hệ </td><td> ".$get_info[0] ."</td></tr>";
echo "<tr><td> Lời nhắn </td><td> ".$get_info[2] ."</td></tr>";
if($get_id[0]['settings'] == "process" || $get_id[0]['settings'] == "unread"|| $get_id[0]['settings'] == "denied") {
echo "<tr><td>Trả lời lời mời</td><td><form action='".urlmanager::makeLink($url[0], "send", $url[2])."' method='post'>
	Nickname của bạn: <input type='hidden' name='my_name' value='".$user_info['user_name']."'/><span style='color:red'>".$user_info['user_name']."</span><br />
	Xác nhận: <select name='reply'><option value='deny'>Từ chối</option><option value='accept' selected >Đồng ý</option></select><br />
	<input value='Trả lời' type='submit' /> </form>
 </td></tr>";
}
echo "<tr><td>Xóa </td><td>".urlmanager::makeLink($url[0], "delete", $url[2],"delete","Xóa")." </td></tr>";
echo "</table></div>";