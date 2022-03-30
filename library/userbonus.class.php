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

class userbonus {
	private $num_message;
	public function __construct() {
		// Before working, we have to check our DB exist or not.
		if(!isset($_CONFIG)) global $_CONFIG;
		$dbsetting = $_CONFIG->getDB();
		if($dbsetting['AUTOCHECK'] == TRUE) $this->createDB();
	}
	public function createDB() {
		$db		= new DBmanager();
		$query	=	"CREATE TABLE IF NOT EXISTS `".PREFIX."userbonus` (
				  `id` int(10) NOT NULL AUTO_INCREMENT,
				  `title` varchar(256) NOT NULL,
				  `content` varchar(1000) NOT NULL,
				  `created_by` varchar(256) NOT NULL,
				  `belong` varchar(256) NOT NULL,
				  `status` varchar(256) NOT NULL,
				  `public` varchar(256) NOT NULL,
				  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				  `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM;
				";
		$db->execute($query);
	}
	public function getBonus($id ='',$belong = '' , $start=0 , $limit=3, $public= 1,$created_by='') {
		$db		= new DBmanager();
		$id		= $db->escapeString($id);
		$belong = $db->escapeString($belong);
		$start	= $db->escapeString($start);
		$limit	= $db->escapeString($limit);
		$select	= ' FROM '.PREFIX.'userbonus ';
		$query	 	= $select; 
		if($public != '')  		$query .= ($select==$query?' WHERE ':' AND ').'  `public` IN ('.$public.') ';
		if($id != '') 			$query .= ($select==$query?' WHERE ':' AND ').'  `id`="'.$id.'" ';
		//if($created != '') 	$query .= ($select==$query?' WHERE ':' AND ').'  `created`="'.$created.'" ';
		if($created_by != '') 	$query .= ($select==$query?' WHERE ':' AND ').'  `created_by`="'.$created_by.'" ';
		//if($modified != '') 	$query .= ($select==$query?' WHERE ':' AND ').'  `modified`="'.$modified.'" ';
		if($belong != '') 		$query .= ($select==$query?' WHERE ':' AND ').'  `belong`="'.$belong.'" ';
		$query_count	= 'SELECT count(id) '.$query;
		$db->execute($query_count);
		$temp = $db->getRow();
		$this->num_message = $temp['count(id)'];
		$query	 = 'SELECT * '.$query;
		$query	.= "  ORDER BY `created`  DESC LIMIT $start,$limit";
		$result  = $db->execute($query);
		$num	 = $db->count();
		if($num > 0) {
			$results = array();
			for($i=0; $i<$num; $i++) {
				$row = $db->FetchResult($result);
				array_push($results,$row);
				if($_SESSION['info']['id'] == $row['belong']) {
					// Update Status if open a messeage.
					$query_update	 = "UPDATE  ".PREFIX."userbonus SET `status`='1' WHERE `id`='$id'";
					$db->execute($query_update);
				}
			}
			return $results;
		}
		else {
			return FALSE;
		}
	}
	public function getNumMessage() {
		return $this->num_message;
	}
	public function isNew($user_id = '' ) {
		$db		= new DBmanager();
		$user_id= $db->escapeString($user_id);
		$query	= 'SELECT * FROM '.PREFIX.'userbonus ';
		if($user_id != '') $query .= ' WHERE `belong`="'.$user_id.'" AND `status`="0"';
		else $query .= ' WHERE `status`="0"';
		$result  = $db->execute($query);
		//$row	 = $db->getRow();
		$num	 = $db->count();
		if($num > 0) {
			$results = array();
			$id_setto = '';
			for($i=0; $i<$num; $i++) {
				$row = $db->FetchResult($result);
				array_push($results,$row);
				$id_setto .= ($i==0)? $row['id']:','.$row['id'];
			}
			// set status -> 1
			$query 		= 'UPDATE '.PREFIX."userbonus  SET `status`  = '1' WHERE `id` IN (".$id_setto.")";
			$db->execute($query);
			return $results;
		}
		else {
			return FALSE;
		}
		//echo $db->error();
		//return $row['count(id)'];
	}
	public function commentNew() {
		$db			 = new DBmanager();
		$community   = new community();
		$friend_list = $community->getConfig('userbonus','community');
		if(empty($friend_list)) {
			$id_get 			= $community->createConfig('userbonus','community','0:-:0');
			$last_comment 		= 0;
			$last_comment_blog  = 0;
		}
		else {
			$id_get = $friend_list[0]['id'];
			list($last_comment,$last_comment_blog) = explode(":-:",$friend_list[0]['value1']);
			if( ! ($last_comment >0) ) 		$last_comment = 0;
			if( ! ($last_comment_blog >0) ) $last_comment_blog = 0;
		}
		$comment 	 = new comment();
		$results = $comment->getcommentUpdate($last_comment,'Front page');
		if (!empty($results) ) {
			$str .= '<numbercom>'.count($results).'</numbercom>';;
			foreach($results as $key=>$value) {
				$last_comment = ($last_comment > $value['id'])? $last_comment : $value['id'];
				$str .= '<commentfg>';
				$str .= '<id>'.$value['id'].'</id>';
				$str .= '<title>'.$value['title'].'</title>';
				$introtext = wrap_content($value ['comment'],0,200);
				$str .= '<comment>'.htmlspecialchars($introtext ).'</comment>';
				$str .= '<date>'.$value['date'].'</date>';
				$str .= '<user>'.htmlspecialchars($value ['user']).'</user>';
				$str .= '</commentfg>';
			}
		}
		else $str .= '<numbercom>0</numbercom>';
		$results = $comment->getcommentUpdate($last_comment_blog,'blog');
		$blog_title=$comment->getBlogTitle();
		if (!empty($results) ) {
			$str .= '<numbercomblog>'.count($results).'</numbercomblog>';
			foreach($results as $key=>$value) {
				$last_comment_blog = ($last_comment_blog > $value['id'])? $last_comment_blog : $value['id'];
				$str .= '<commentblog>';
				$str .= '<id> '.$value['id'].'</id>';
				$str .= '<title> '.$value['title'].'</title>';
				$str .= '<belong> '.$blog_title[$value['belong']]['title'].'</belong>';
				$introtext = wrap_content($value ['comment'],0,200);
				$str .= '<comment> '.htmlspecialchars($introtext ).'</comment>';
				$str .= '<date> '.$value['date'].'</date>';
				$str .= '<user> '.htmlspecialchars($value ['user']).'</user>';
				$str .= '</commentblog>';
			}
		}
		else $str .= '<numbercomblog>0</numbercomblog>';
		// update agaisn to own DataBase
		$community->setConfig($id_get,'userbonus','community',$last_comment.':-:'.$last_comment_blog );
		return $str;
	}
	/**
	 * Return list of friend after checking
	 * Enter description here ...
	 * @param array $num key 1: Limit from - key 2: Limit
	 */
	public function returnFriendList($limit=array(0,10)) {
		if($_SESSION['news_friend_noticed']['done'] == true) { return $_SESSION['news_friend_noticed']['value']; }
		$community = new community();
		$friend_list = $community->getConfig('makefriend','community','','friend','','','','',$limit);
		//print_r($friend_list);
		if(!empty($friend_list)) {
			foreach($friend_list as $value) {
				$str .=  "<friend>";
				$get_info	 	= explode(":-:",$value['value3']);
				$get_last	 	= explode(":-:",$value['value4']);	// value4 for save lastID own blog read
				if( !($get_last[0] >0) ) $get_last[0] = 0;
				if( !($get_last[1] >0) ) $get_last[1] = 0;
				if( !($get_last[2] >0) ) $get_last[2] = 0;
				$str .=  "<name>".$value['value2']."</name>";
				$str .=  "<url>".$get_info[2]."</url>";
				if(substr($get_info[2] , -1) != "/" )		$get_info[2] = $get_info[2]."/";
				$result = sendData($get_info[2]."xml.php?type=friendupdate&lastContentID=".$get_last[0]."&lastCommentID=".$get_last[1]."&lastCommentBlogID=".$get_last[2],null,"GET","");
				$result_xml = simplexml_load_string($result);
				if($result == false) {
					$str .= '<error>Can not connect to '.$get_info[2].'. Please check this blog link.</error>';
					$str .=  "</friend>";
					continue;
				}
				elseif($result_xml === false) {
					$str .= '<error>Connected to '.$get_info[2].' BUT Can not recognize the type of blog standard.</error>';
					$str .=  "</friend>";
					continue;
				}
				$str .= '<error>No</error>'; // No error
				$result = str_replace("<?xml version='1.0' encoding='UTF-8' ?>","",$result);
				$result = str_replace("<friendupdate>","",$result);
				$result = str_replace("</friendupdate>","",$result);
				$str .= $result;
				$last_id_blog =  ($get_last[0] >= $result_xml ->last_id_blog[0])?$get_last[0]:$result_xml ->last_id_blog[0] ;
				$last_id_com =  ($get_last[1] >= $result_xml ->last_id_com[0])?$get_last[1]:$result_xml ->last_id_com[0]  ;
				$last_id_comblog =  ($get_last[2] >= $result_xml ->last_id_comblog[0] )?$get_last[2]:$result_xml ->last_id_comblog[0] ;
				$str .=  "</friend>";
				// update agaisn to own DataBase after get successfully
				$community->setConfig($value['id'],'','','','','',$last_id_blog.':-:'.$last_id_com.':-:'. $last_id_comblog);
			}
			//$_SESSION['news_friend_noticed']['value'] = '';
			$_SESSION['news_friend_noticed']['done']   = false;
			$_SESSION['news_friend_noticed']['value'] .= $str;
			$str = $_SESSION['news_friend_noticed']['value'];
			$xml = simplexml_load_string("<?xml version='1.0' encoding='UTF-8' ?><root>".$str.'</root>');
			$num_friend = count($xml->friend);
			$str = "<friendnumber>".$num_friend."</friendnumber>".$str;
			$str = "<friendlist>". $str ."</friendlist>";
			return $str;
		}
		else {
			$_SESSION['news_friend_noticed']['done'] = true;
			$str = $_SESSION['news_friend_noticed']['value'];
			$xml = simplexml_load_string("<?xml version='1.0' encoding='UTF-8' ?><root>".$str.'</root>');
			$num_friend = count($xml->friend);
			$str = "<friendnumber>".$num_friend."</friendnumber>".$str;
			$str = "<friendlist>". $str ."</friendlist>";
			$_SESSION['news_friend_noticed']['value'] = $str;
			return;		//$str .= "<friendnumber>0</friendnumber>";
		}
	}
	public function updateBonus($id,$title,$content,$created,$modified,$belong,$status = 0,$public = 1) {
		$db 		= new DBmanager();
		$id			= $db->escapeString($id);
		$title		= $db->escapeString($title);
		$content	= $db->escapeString($content);
		$created 	= $db->escapeString($created);
		$modified 	= $db->escapeString($modified);
		$belong 	= $db->escapeString($belong);
		$status 	= $db->escapeString($status);
		$public 	= $db->escapeString($public);
		if($modified == '') {
			$homnay=date(dmYHis);
				$modified=substr($homnay,4,4)."-".substr($homnay,2,2)."-".substr($homnay,0,2)." ".(substr($homnay,8,2)-1)."-".substr($homnay,10,2)."-".substr($homnay,12,2);
		}
		$query 		= 'UPDATE '.PREFIX."userbonus  
						SET `title` 	 = '$title',
							`content`	 = '$content',
							`created`	 = '$created',
							`modified`   = '$modified',
							`belong` 	 = '$belong',
							`status` 	 = '$status',
							`public` 	 = '$public'
						WHERE `id` ='$id'
									";
		$suc = $db->execute($query);
		if($suc == FALSE) return FALSE;
		else return TRUE;
	}
	// createBounus for member in this site
	public function createBounus($title,$content,$created,$belong,$public=1,$created_by="") {
		if($belong >0 ) {
			$db = new DBmanager();
			$title		= $db->escapeString($title);
			$content	= $db->escapeString($content);
			$created 	= $db->escapeString($created);
			$belong 	= $db->escapeString($belong);
			$public 	= $db->escapeString($public);
			if($created_by == '') $created_by = $_SESSION['info']['id'];
			//echo $fulltext;
			if($created == '') $created = 'CURRENT_TIMESTAMP';
			$query 		= 'INSERT INTO '.PREFIX."userbonus ( `title`, `content`,  `created`, `created_by`, `public`,`belong`,`status`) VALUES ('$title',  '$content', '$created', '$created_by',   '$public', '$belong','0')";
			$suc = $db->execute($query);
		}else {
			return $this->sendBounus($title,$content,$created,$belong,$public,$created_by);
		}
		if($suc == FALSE) return FALSE;
		else {
			return TRUE;
		}
	}
	// send messenger for friend in other site
	public function sendBounus($title,$content,$created,$belong,$public=1,$created_by="") {
		// $belong like :    user:-:url
		list($user_name,$url_friend) = explode(":-:",$belong);
		//echo $fulltext;
		if($created == '') $created = 'CURRENT_TIMESTAMP';
		if($created_by == '') $created_by = $_SESSION['info']['id'];
		$created_by_name = user::getUserByID( $created_by,'id');
		$url = substr(_URL,0,-1);
		$data = array("title"=>($title),
						"content"=>($content),
						"created"=>$created,
						"public"=>$public,
						"belong"=>$belong,
						"status"=>1,
						"created_by"=>$created_by_name,
						"url"=>$url);
		if(substr($url_friend , -1) != "/" )		$url_friend = $url_friend."/";
		$result = sendData($url_friend."xml.php?type=community&name=userbonus",$data,"POST","xml");
		if($result->completed == "TRUE") {
			// save massage in own database 
			$db = new DBmanager();
			$query 		= 'INSERT INTO '.PREFIX."userbonus ( `title`, `content`,  `created`, `created_by`, `public`,`belong`,`status`) VALUES ('$title',  '$content', '$created', '$created_by',   '$public', '$belong','0')";
			$suc = $db->execute($query);
			return TRUE;
		}
			return FALSE;
		
	}
	public function deleteBonus($id) {
		$db = new DBmanager();
		$id = $db->escapeString($id);
		if(!isset($login) )global $login;
		$user_info = $login->getInfo();
		if($user_info['user_type'] != '3' && $user_info['user_type'] != '4' ) {
			$info = $this->getBonus($id);
			if($info[0]['belong'] != $user_info['user_id']) return FALSE;
		}
		$query = 'DELETE FROM '.PREFIX.'userbonus WHERE `id`="'.$id.'" LIMIT 1';
		$suc = $db->execute($query);
		if($suc == FALSE ) {
			return FALSE;
		}
		else return TRUE;
		
	}
}







?>