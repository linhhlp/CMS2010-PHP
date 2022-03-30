<?php
/**
 * @author HLP4ever .
 * @version		2010
 * @package		HLP4ever community.
 * @copyright	Copyright (C) 2010 by HLP.
 * @license		Open Source and free for non-conmecial
 */
defined('START') or die;
/**
 * Do not change code below, only set up your config if you're sure you've known.
 */
$invitation = $community->getConfig('makefriend','community','','invitation');
echo "<div class='makefriend'><h2>Quản lý lời mời</h3>";
echo "<br />";
// See all comming-in invitation 
// settings of invitation:
//unread  : the invitation which is unread.
//process : the invitation which is read but still not be decided.
//accepted: the invitation which is accepted.
//denied  : the invitation which is denied.
$num_in  = 0; $num_out 	  = 0;
$unread	 = 0; $unread_out  = 0;
$process = 0; $process_out = 0;
$accepted= 0; $accepted_out= 0;
$denied  = 0; $denied_out  = 0;
if(!empty($invitation)) {
foreach($invitation as $key=>$value) {
		if($value['value2'] == 'in' ) {
			//split content of an invitation
			// 0: friendtype     1: friendname          2: comment      3: url 	  4: id of come-out invitation
			// id: row id		settings: status read or unread.
			$invitation_in[$num_in] 		= explode(":-:",$value['value3']);
			$invitation_in[$num_in]['id']	= $value['id'];
			$invitation_in[$num_in]['settings']= $value['settings'];
			$friend_type					= explode(":_-_:", $invitation_in[$num_in][0]);
			$invitation_in[$num_in][0] = "<span class='mkfr_emp'>" .$friend_type[1]."</span> và <span class='mkfr_emp'>" .$friend_type[0]."</span>";
			switch($value['settings']) {
				case "unread";  $unread++;	break;
				case "process"; $process++;	break;
				case "accepted";$accepted++;break;
				case "denied"; 	$denied++;	break;
			}
			$num_in++;
		}
		elseif($value['value2'] == 'out' ) {
			//split content of an invitation
			$invitation_out[$num_out] 		= explode(":-:",$value['value3']);
			$invitation_out[$num_out]['id']	= $value['id'];
			$invitation_out[$num_out]['settings']= $value['settings'];
			$friend_type 					= explode(":_-_:", $invitation_out[$num_out][0]);
			$invitation_out[$num_out][0] = "<span class='mkfr_emp'>" .$friend_type[0]."</span> và <span class='mkfr_emp'>" .$friend_type[1]."</span>";
			switch($value['settings']) {
				case "unread";  $unread_out++;	break;
				case "process"; $process_out++;	break;
				case "accepted";$accepted_out++;break;
				case "denied"; 	$denied_out++;	break;
			}
			$num_out++;
		}

}
echo "Bạn có ".$num_in." thư mời. Có ".$unread." thư chưa đọc, ".$process." thư chờ xử lý, ".$accepted." thư chấp nhận và ".$denied." thư từ chối. <br />";
if( $num_in > 0) {
	echo "<div class='mkfr_from'><h3>Thư mời bạn đã nhận</h3>";
	echo "<table border=1 width=100%><tr>  <td>Từ địa chỉ </td><td> Tên </td><td>Mối quan hệ </td><td> Lời nhắn </td><td> Trạng thái </td><td>Xóa </td></tr>";
	for($i =0; $i< $num_in; $i++) {
		echo "<tr>  <td width=25%>".urlmanager::makeLink($url[0], "view", $invitation_in[$i]['id'],"view",$invitation_in[$i][3]) ."</td>"."<td  width=20%>".$invitation_in[$i][1] ."</td><td width=10%> ".$invitation_in[$i][0] ."</td><td  width=30%> ".$invitation_in[$i][2] ."</td>";
		echo "<td> ";
		switch($invitation_in[$i]['settings']) {
				case "unread";  echo "Chưa đọc";	break;
				case "process"; echo "Chờ xử lý";	break;
				case "accepted";echo "Chấp nhận";break;
				case "denied"; 	echo "Từ chối";	break;
			}
		echo "</td><td>".urlmanager::makeLink($url[0], "delete", $invitation_in[$i]['id'],"delete","Xóa")." </td></tr>";
	}
	echo "</table></div>";
}
else {
	echo "<h4>Bạn không nhận được thư mời nào</h4>";
}
if( $num_out > 0) {
	echo "<div class='mkfr_from'><h3>Thư mời bạn đã gửi</h3>";
	echo "<table border=1 width=100%><tr>  <td>Từ địa chỉ </td><td> Tên </td><td>Mối quan hệ </td><td> Lời nhắn </td><td> Trạng thái </td><td>Xóa </td></tr>";
	for($i =0; $i< $num_out; $i++) {
		echo "<tr>  <td width=25%>".$invitation_out[$i][3] ."</td>"."<td  width=20%>".$invitation_out[$i][1] ."</td><td width=10%> ".$invitation_out[$i][0] ."</td><td  width=30%> ".$invitation_out[$i][2] ."</td>";
		echo "<td> ";
		switch($invitation_out[$i]['settings']) {
				case "not send";  echo "Không gửi được</td><td>".urlmanager::makeLink($url[0], "delete", $invitation_out[$i]['id'],"delete","Xóa")."</td>";	break;
				case "sent"; echo "Đã gửi</td><td>Không xóa</td>";	break;
				case "accepted";echo "Được chấp nhận</td><td>".urlmanager::makeLink($url[0], "delete", $invitation_out[$i]['id'],"delete","Xóa")." </td>";break;
				case "denied"; 	echo "Bị từ chối</td><td>".urlmanager::makeLink($url[0], "delete", $invitation_out[$i]['id'],"delete","Xóa")." </td>";	break;
			}
		echo "</tr>";
	}
	echo "</table></div>";
}
else {
	echo "</h4>Bạn chưa gửi thư mời nào<h4>";
}
} // end if (!empty($invitation))
echo "</div>";	// div class= makefriend

?>