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
// example url request: xml.php?type=community&name=makefriend
echo "<makefriend>";
if(empty($_CONFIG) )	global $_CONFIG;
$site_config= $_CONFIG->getSite();
$inviation	= $_POST['invite'];
//process to main url like: from "http://example.com/abc/xyz" to "example.com" only
if($inviation == "invite") {
	// Đây là 1 lời mời kết bạn
	$friendtype = $_POST['friendtype'];
	$friendname = $_POST['friendname'];
	$comment	= $_POST['comment'];
	$url		= $_POST['url'];
	$invi_id	= $_POST['id'];
	if(substr($url,0,7) == "http://" || substr($url,0,8) == "https://") {
		$url_a = explode("//",$url);
		$url_c		= $url_a[1];
	}
	else $url_c = $url;
	$url_a		= explode("/",$url_c);
	$url_c		= $url_a[0];
	$url_check  = gethostbyname($url_c);
	$ip_send	= $_SERVER['REMOTE_ADDR'];
	if($ip_send == $url_check ) {
		$community = new community();
		// 0: friendtype     1: friendname          2: comment      3: url	  4: id of come-out invitation
		// 	id: row id		settings: status read or unread.
		$que_result= $community->createConfig('makefriend','community', 'invitation','in',$friendtype.":-:".$friendname .":-:".$comment.":-:".$url.":-:".$invi_id,"","unread");  
		if( $que_result != FALSE ) $complete = TRUE;
		else $complete = FALSE;
	}
	else $complete = FALSE;
	

}	
elseif($inviation == "reply") {
	$reply		= $_POST['reply'];
	$invi_id	= $_POST['id'];
	$friend_name	= $_POST['friendname'];
	// Đây là 1 thư trả lời việc kết bạn hay không?
	$community = new community();
	// check the invitation exist or not
	$friend_info = $community->getConfig("","",$invi_id);
	if($friend_info[0]['settings'] == "sent" || $friend_info[0]['settings'] == "denied") {
		if($reply == "deny")	$community->setConfig($invi_id,"","","","","","","denied");
		if($reply == "accept")	{
			$community->setConfig($invi_id,"","","","","","","accepted");
			// save the friend information
			$friend		 = explode(":-:",$friend_info[0]['value3']);
			//$friend_type = explode(":_-_:", $friend[0]);
			//$friend[0]	 = $friend_type[1].":_-_:" .$friend_type[0];
			//$friend_name = $friend[1].":_-_:" .$friend_name;
			// 0: friendtype     1: friendname             2: url	
			$community->createConfig('makefriend','community', 'friend',$friend_name ,$friend[0].":-:".$friend[1].":-:".$friend[3]);
		}
		$complete = TRUE;
	}
	else $complete = FALSE;
}
if($complete == TRUE) {
	echo "<completed>TRUE</completed>";
}
else {
	echo "<completed>FALSE</completed>";
}
echo "</makefriend>";

?>