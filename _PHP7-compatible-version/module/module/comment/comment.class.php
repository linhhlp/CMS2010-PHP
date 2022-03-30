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
 * Note: Only 4 comments a day per a IP.
 * 
 *
 */
class comment {
	private $error;
				// Code number 1: Get Limit comments
				// Code number 2: Can not query DB
				// Code number 3: Your name or comment empty
				// Code number 4: Exist user name (registered user)
	private $num_comment;
	private $id_belongs;
	public function __construct() {
		if(!isset($_CONFIG)) global $_CONFIG;
		$dbsetting = $_CONFIG->getDB();
		if($dbsetting['AUTOCHECK'] == TRUE) $this->createDB();
	}
	public function createDB() {
		$db = new DBmanager();
		$query	=	"CREATE TABLE IF NOT EXISTS `".PREFIX."comment` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `title` varchar(256) NOT NULL,
				  `comment` mediumtext NOT NULL,
				  `type` varchar(256) NOT NULL,
				  `belong` varchar(256) NOT NULL,
				  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				  `ip` varchar(256) NOT NULL,
				  `user` varchar(256) NOT NULL,
				  `publish` varchar(256) NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM;
				";
		$db->execute($query);
	}
	public function getAcomment($id) {
		// Get only A comment by ID.
		$db		 = new DBmanager();
		$id 	 = $db->escapeString ( $id );
		$query	= 'SELECT * FROM '.PREFIX.'comment WHERE `id`="'.$id.'"  ';
		if($db->execute($query) != FALSE) {
			$row = $db->getRow();
			$this->id_belongs = $row['belong'];
			return $row;
		}
		else return FALSE;
	}
	public function getComments($type, $limit_start =0, $limit = 3,$belong ='',$publish ='', $ip ='',$user = '') {
		$db		 = new DBmanager();
		$type 	 = $db->escapeString ( $type );
		$limit 	 = $db->escapeString ( $limit );
		$user 	 = $db->escapeString ( $user );
		$belong  = $db->escapeString ( $belong );
		$ip		 = $db->escapeString ( $ip );
		$publish = $db->escapeString ( $publish );
		// Default setting
		if($limit_start =='') 	$limit_start = 0;
		$query_count	= 'SELECT count(id) FROM '.PREFIX.'comment WHERE `type`="'.$type.'" ';
		if($publish == '') {
  if($_SESSION['type'] == 3 || $_SESSION['type'] == 4 ) $publish = '';
  elseif($_SESSION['type'] == 2 || $_SESSION['type'] == 1 ) $publish = '"1","2"';
  else $publish = '"1"';
}
		if($publish != '') $query_count.= ' AND `publish` IN ('.$publish.') ';
		if($belong != '') 	$condition .= ' AND `belong`="'.$belong.'" ';
		if($ip != '') 		$condition .= ' AND `ip`="'.$ip.'" ';
		if($user != '') 	$condition .= ' AND `user`="'.$user.'" ';
		$db->execute($query_count.$condition);
		$temp = $db->getRow();
		$this->num_comment = $temp['count(id)'];
		$query	= 'SELECT * FROM '.PREFIX.'comment WHERE `type`="'.$type.'" ';
		if($publish != '') 		$query 	 .= ' AND`publish` IN ('.$publish.') ';
		if(!empty($limit))	$limit_query .= ' ORDER BY `date`  DESC LIMIT '.$limit_start.','.$limit.' ';
		else 	$limit_query .= ' ORDER BY `date`  DESC ';
		$result = $db->execute($query.$condition.$limit_query);
		$num = $db->count();
		if($num > 0) {
			$results = array();
			// Find All user name who posted comment
			//$user_check = $this->getUserName();
			//
			for($i=0; $i<$num; $i++) {
				$row = $db->FetchResult($result);
				//$row['comment'] = html_entity_decode($row['comment']); 
				//if($user_check[$row['user']] != '') $row['user'] = $user_check[$row['user']];
				$i==0?$this->id_belongs .= $row['belong']:$this->id_belongs .= ','.$row['belong'];
				array_push($results,$row);
			}
			return $results;
		}
		else {
			return FALSE;
		}
	}
	public function getUserName($id='') {
		if($id == ''	&&	$this->id_belongs != '') $id = $db->escapeString($this->id_belongs);
		return user::getUserName($id);
	}
	public function getBlogTitle($id='') {
		$db		  = new DBmanager();
		if($id == '') $id = $db->escapeString($this->id_belongs);
		$query	  = 'SELECT id,title,created_by FROM '.PREFIX.'blog WHERE `id` IN ('.$id.') ';
		$db->execute($query);
		$num =$db->count();
		//echo $num;
		if($num > 0) {
			$result = array();
			$id_arr = array();
			for($i=0; $i< $num; $i++) {
				$row = $db->getRow();	
				array_push($result,$row);
				array_push($id_arr,$row['id']);
			}
			$blog_title = array_combine($id_arr,$result);
			return $blog_title;
		}
		else return FALSE;
	}
	
	public function getNumCom(){
		return $this->num_comment;
	}
	
	public function updateComment($id,$title, $comment, $belong = '', $user = '', $date = '',  $publish = '') {
		$db		 = new DBmanager();
		$id 	 = $db->escapeString ( $id );
		$title 	 = $db->escapeString ( $title );
		$comment = $db->escapeString ( $comment );
		$belong  = $db->escapeString ( $belong );
		$user 	 = $db->escapeString ( $user );
		$date  	 = $db->escapeString ( $date );
		$publish = $db->escapeString ( $publish );
		// Default setting			
		$query	="UPDATE ".PREFIX."comment SET `title`='$title',`comment`='$comment' ,`user`='$user',`date`='$date' ,`publish`='$publish',belong='$belong'  WHERE id='$id'";
		$suc	= $db->execute($query);
		return $suc;
	}
	
	public function deleteComment($id) {
		$db = new DBmanager();	
		$xoa ="DELETE FROM  ".PREFIX."comment WHERE id='$id' LIMIT 1";	
		$suc	= $db->execute($xoa);
		return $suc;
	}
	
	public function createComment($title, $comment, $type, $belong = '', $user = '', $date = '', $ip = '', $publish = '',$max_comment = 8) {
		$db = new DBmanager ();
		global $_CONFIG;
		if (! isset ( $_CONFIG ))	$_CONFIG = new config ();
		if ($user == '') 	$user = $_SESSION['user'];
		if ($user == '')	$user = 'Guest';
		$title 	 = $db->escapeString ( $title );
		$title	 = htmlspecialchars($title);
		$comment = $db->escapeString ( $comment );
		//$comment = htmlspecialchars($comment);
		$type 	 = $db->escapeString ( $type );
		$user 	 = $db->escapeString ( $user );
		$belong  = $db->escapeString ( $belong );
		$date 	 = $db->escapeString ( $date );
		$ip		 = $db->escapeString ( $ip );
		$publish = $db->escapeString ( $publish );
		// Default setting
		if($ip =='') 		$ip = $_SERVER['REMOTE_ADDR'];
		if($publish =='') 	$publish = 1;
		if($date =='') {
			$homnay	=date(dmYHis);
			$date	=substr($homnay,4,4)."-".substr($homnay,2,2)."-".substr($homnay,0,2)." ".(substr($homnay,8,2)-1)."-".substr($homnay,10,2)."-".substr($homnay,12,2);
		}
		$com_hlp = $this->filter ( $comment );
		//Start
		if (($title != '') && ($com_hlp != '')) {
			if($_SESSION["info"]["type"]  == 0) {
				// Check spam : Only 4 comments per day
				$check_query = "SELECT count(id) FROM  " . PREFIX . "comment WHERE `publish`=1 AND `ip`='$ip' AND `type`= '$type' ";
				if($belong != '') 	$check_query .= " AND `belong`='$belong'";
				$db->execute ( $check_query );
				$check_num = $db->getRow ();
				if ($check_num['count(id)'] > $max_comment) {
					$this->error = 1; // Code number 1: Get Limit comments

				}
			}

			// Check name user exists or not 
				/*
				if($_SESSION ['user'] != $user) {
					$user_check = 'SELECT id FROM '.PREFIX.'user WHERE user="'.$user.'" ';
					$user_exist = $db->execute($user_check);
					if($user_exist != FALSE) $this->error = 4;	// Code number 4: Exist user name (registered user)
				}
				*/
			if(empty($this->error)) {
				$com_query = "INSERT INTO  " . PREFIX . "comment  (`title` , `comment` , `ip`,`type`,`belong`,`publish`,`user`) VALUES ('$title' , '$com_hlp' , '$ip','$type' , '$belong','1','$user')";
				$suc = $db->execute ( $com_query );
				if ($suc != FALSE) {
					/*include_once ("thu_vien/mail.php");
					//gui_mail ( "", "", "Blog vừa có thêm bình luận của bài viết mới, <br><a href=http://" . $_SERVER ['SERVER_NAME'] . "/?hlp=blog&id=" . $id . "> Nhấn vào đây để xem</a> ", "", "__1___" );
					echo 'Bạn đã gửi bình luận thành công<br>';
					*/						
					return TRUE;
				} else
					$this->error = 2; // Code number 2: Can not query DB
			}
			
		} 
		else
			$this->error = 3; // Code number 3 : Your name or comment empty
	}
	
	public function filter($string) {
		$string = nl2br ( $string );
		return $string;
	}
	public function getError() {
		return $this->error;
	}
}
?>