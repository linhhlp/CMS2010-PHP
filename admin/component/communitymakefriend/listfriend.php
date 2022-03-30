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
 *  This feature list all your friend
 *  
 */
echo "<h2>Quản lý danh sách bạn bè</h3>";
echo "<br />";
$friend_list = $community->getConfig('makefriend','community','','friend');
if($friend_list != FALSE) {
	echo "<table border=1 width=100%><tr>  <td>Từ địa chỉ </td><td>Tên của khach</td><td> Tên của mình</td><td>Mối quan hệ </td></tr>";
	foreach ($friend_list as $key=>$value) {
		// 0: friendtype     1: friendname            2: url 	
		$get_info	 	= explode(":-:",$value['value3']);
		$friend_type	= explode(":_-_:", $get_info[0]);
		$get_info[0]	= "<span class='mkfr_emp'>" .$friend_type[0]."</span> và <span class='mkfr_emp'>" .$friend_type[1]."</span>";
		//$friend_name	= explode(":_-_:", $get_info[1]);
		// 0: own name		1: name of friend
		echo "<tr><td>".$get_info[2]."</td><td>".$value['value2']."</td><td>".$get_info[1]."</td><td>".$get_info[0]."</td></tr>";
	}
	echo "</table>";
}
else echo "Bạn không có bạn bè nào";