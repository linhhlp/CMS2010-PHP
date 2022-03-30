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
// example url request: xml.php?type=community&name=userbonus
echo "<userbonus>";
$complete = FALSE;
if(empty($_CONFIG) )	global $_CONFIG;
$site_config= $_CONFIG->getSite();
//process to main url like: from "http://example.com/abc/xyz" to "example.com" only
$community_setting = $_CONFIG->getCommunity();
// if enable for commnunity
if($community_setting["ENABLE"] == TRUE) {
	$created_by	= $_POST['created_by'];
	$url_sender	= $_POST['url'];
	// firstly, check in Database, there is this friend or not. If not, do not save message.
	$community = new community();
	$friend_list = $community->getConfig('makefriend','community','','friend',$created_by);
	foreach($friend_list as $key=>$value) {
		$friend_list_split = explode(":-:",$value['value3']);
		if(substr($url_sender , -1) != "/" )		$url_sender = $url_sender."/";
		if($url_sender == $friend_list_split[2]) {
			$friendtype_2 = explode(":_-_:",$friend_list_split[0]);
			$temp_array = explode(":-:",$_POST['belong']);
			$get_id 	= user::getIDByUser($temp_array[0]);
			$userbonus	= new userbonus(); 
			$suc = $userbonus->createBounus($_POST['title'],$_POST['content'],$_POST['created'],$get_id,$_POST['public'],$friendtype_2[1].": ".$created_by." ở ".$url_sender	);
			$complete = TRUE;
			echo "<completed>TRUE</completed>";
		}
	}
	
}	


if($complete == FALSE) {
	echo "<completed>FALSE</completed>";
}
echo "</userbonus>";

?>