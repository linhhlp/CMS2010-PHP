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
$send_param[0] = $url[2];
if( $send_param[0] > 0) {
	$my_name 	  = $_POST['my_name'];
	$send_param[1]= $_POST['reply']; // accept or deny
	$get_id 	  = $community->getConfig("","",$url[2]);
	$friend 	  = explode(":-:",$get_id[0]['value3']);
	// Process an invitation
	$data = array("id"=>$friend[4],"invite"=>"reply","reply"=>$send_param[1],"friendname"=>$my_name);
	if(substr($friend[3] , -1) != "/" )		$friend[3] = $friend[3]."/"; 
	$result = sendData($friend[3]."xml.php?type=community&name=makefriend",$data,"POST","xml");
	if($result->completed == "TRUE" && $send_param[1] == "accept") {
		// 0: friendtype     1: friendname             2: url	
		$friend_type = explode(":_-_:", $friend[0]);
		$friend[0] = $friend_type[1].":_-_:" .$friend_type[0];
		$community->createConfig('makefriend','community', 'friend',$friend[1],$friend[0].":-:". $my_name.":-:".$friend[3]);
		$community->setConfig( $send_param[0],"","","","","","","accepted");
		echo "Đã gửi thành công thư trả lời. Bạn đã tạo thành công mối quan hệ mới.";
	}
	elseif($result->completed == "TRUE"  && $send_param[1] == "deny") {
		echo "Bạn đã từ chối thành công";
		$community->setConfig( $send_param[0],"","","","","","","denied");
	}
	elseif($result->completed != "TRUE" ) {
		echo "Không thể gửi trả lời, vui lòng kiểm tra lại kết nối cũng như địa chỉ trang web";
	}
	 
}
else {
	echo "<h3>Gửi một lời mời kết bạn</h3>";
	// make an invitation
	// 0: friendtype     1: friendname          2: comment      3: url	  4: id of come-out invitation
	// 	id: row id		settings: status read or unread.
	if($_POST['url'] != "") {
		$friendtype1 = str_replace(":_-_:"," ",$_POST['friendtype1']);
		$friendtype2 = str_replace(":_-_:"," ",$_POST['friendtype2']);
		$friendtype	 = $friendtype1.":_-_:".$friendtype2;
		$friendname  = $_POST['friendname'];
		$comment	 = $_POST['comment'];
		$send_to	 = $_POST['url'];
		// find own url
		$url_param = explode("/", $_SERVER["SCRIPT_NAME"]);
		$url_param_num = count($url_param);
		$url_script_root = "";
		for($i =0; $i < $url_param_num -2; $i++) {
			$url_script_root .= $url_param [$i]."/";
		}
		//$my_url	= 'http://'.$_SERVER['SERVER_NAME'].($_SERVER["SERVER_PORT"] != 80?$_SERVER["SERVER_PORT"]:'').$url_script_root;
        $my_url = _URL;
		// end find own url
		$id_insert	 = $community->createConfig('makefriend','community', 'invitation','out',$friendtype.":-:".$friendname .":-:".$comment.":-:".$send_to,"","not sent ");  
		if( $id_insert != FALSE ) {
			$data = array("id"=>$id_insert,"invite"=>"invite","url"=>$my_url,"comment"=>$comment,"friendtype"=>$friendtype,"friendname"=>$friendname);
			if(substr($send_to , -1) != "/" )		$send_to = $send_to."/";
			$result = sendData($send_to."xml.php?type=community&name=makefriend",$data,"POST","xml");
			if($result->completed != "FALSE" ) {
				echo "Gửi lời mời thành công";
				$community->setConfig($id_insert,"","","","","","","sent");
			}
			else {
				echo "Không thể gửi trả lời, vui lòng kiểm tra lại kết nối cũng như địa chỉ trang web";
			}
		}
		else {
			echo "Không thể tạo được thư mời.";
		}
	}
	else {
		if(!isset($login) )global $login;
		$user_info = $login->getInfo();
		
		echo "<form action='' method='post'><table border='0' width=60%> ";
		echo "<tr><td width=30%> Nickname: </td><td><input name='friendname' value='".$user_info['user_name']."' type='text' /> </td></tr>";
		echo "<tr><td> Mối quan hệ: </td><td>Chiều bạn là:<input name='friendtype1' value='Bạn bè' type='text' size='10' /> <br />
										Chiều kia là: <input name='friendtype2' value='Bạn bè' type='text' size='10'/> </td></tr>";
		echo "<tr><td> Địa chỉ trang web</td><td><input name='url' value='http://' type='text' /> </td></tr>";
		echo "<tr><td> Lời nhắn</td><td><textarea name='comment'>Rất vui khi được kết bạn với bạn</textarea> </td></tr>";
		echo "<tr><td>Xác nhận </td><td><input value='Gửi' type='submit' /> <input value='Xóa' type='reset' /></td></tr>";
		echo "</table></form>";
	}
	
}

?>